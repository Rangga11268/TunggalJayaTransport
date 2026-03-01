<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsContentSanitizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin cannot save dangerous HTML via content field
     */
    public function test_news_content_is_sanitized_on_store(): void
    {
        $user = \App\Models\User::factory()->admin()->create(['phone_verified_at' => now()]);

        $dangerousContent = '<script>alert("XSS")</script><p>Safe content</p>';

        $this->actingAs($user)
            ->post(route('admin.news.store'), [
                'title' => 'Test Article',
                'content' => $dangerousContent,
                'excerpt' => 'Test excerpt',
                'category_id' => null,
                'is_published' => true,
            ])
            ->assertRedirect();

        // Verify script tag was removed
        $article = NewsArticle::where('title', 'Test Article')->first();
        $this->assertNotNull($article);
        $this->assertStringNotContainsString('<script>', $article->content);
        $this->assertStringContainsString('<p>Safe content</p>', $article->content);
    }

    /**
     * Test safe_content accessor sanitizes allowed tags
     */
    public function test_safe_content_accessor_sanitizes(): void
    {
        $author = User::factory()->create();
        $article = NewsArticle::create([
            'title' => 'Test',
            'slug' => 'test',
            'content' => '<p>Safe</p><script>alert("bad")</script><img src=x onerror="alert(1)">',
            'excerpt' => 'Test',
            'author_id' => $author->id,
            'is_published' => true,
        ]);

        $safeContent = $article->safe_content;

        // Should have safe tags
        $this->assertStringContainsString('<p>Safe</p>', $safeContent);
        // Should not have script
        $this->assertStringNotContainsString('<script>', $safeContent);
        // Should not have onerror attribute
        $this->assertStringNotContainsString('onerror', $safeContent);
    }

    /**
     * Test frontend renders safe_content, not raw content
     */
    public function test_frontend_uses_safe_content_accessor(): void
    {
        $author = User::factory()->create();
        $article = NewsArticle::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => '<p>Hello</p><script>alert("XSS")</script>',
            'excerpt' => 'Test',
            'author_id' => $author->id,
            'is_published' => true,
        ]);

        // Frontend should render using safe_content
        $response = $this->get(route('frontend.news.show', $article->slug));

        $response->assertStatus(200);
        // Verify response has sanitized content
        $response->assertInertia(
            fn($page) =>
            $page->where('article.safe_content', $article->safe_content)
                ->missing('article.content.script')
        );
    }

    /**
     * Test allowed HTML tags are preserved
     */
    public function test_allowed_html_tags_preserved(): void
    {
        $author = User::factory()->create();
        $allowedContent = '<p>Paragraph</p><br><b>Bold</b><i>Italic</i><u>Underline</u><strong>Strong</strong><em>Emphasis</em><ul><li>Item</li></ul><ol><li>Number</li></ol><a href="https://example.com">Link</a><img src="https://example.com/image.jpg" alt="Image">';

        $article = NewsArticle::create([
            'title' => 'Test',
            'slug' => 'test',
            'content' => $allowedContent,
            'excerpt' => 'Test',
            'author_id' => $author->id,
            'is_published' => true,
        ]);

        $safeContent = $article->safe_content;

        // All allowed tags should be present
        $this->assertStringContainsString('<p>', $safeContent);
        $this->assertStringContainsString('<b>', $safeContent);
        $this->assertStringContainsString('<i>', $safeContent);
        $this->assertStringContainsString('<a href', $safeContent);
        $this->assertStringContainsString('<img src', $safeContent);
    }
}
