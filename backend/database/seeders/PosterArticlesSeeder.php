<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PosterArticlesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or Create Category
        $category = Category::firstOrCreate(
            ['slug' => 'berita'],
            ['name' => 'Berita', 'description' => 'Berita Utama & Informasi']
        );

        // 2. Get Admin User (as author)
        // Use Spatie's role scope if available, or just take the first user
        try {
            $author = User::role('admin')->first();
        } catch (\Exception $e) {
            $author = null;
        }

        if (!$author) {
            $author = User::first();
        }

        // If still no user, create one dummy
        if (!$author) {
            $author = User::factory()->create([
                'name' => 'Admin Seeder',
                'email' => 'admin@seeder.com',
            ]);
        }

        // 3. Define Articles Data
        $articles = [
            [
                'title' => 'Info Jadwal Perjalanan Terbaru & Terupdate',
                'slug' => 'info-jadwal-perjalanan-terbaru',
                'image_file' => 'bentas01.webp',
                'excerpt' => 'Dapatkan informasi lengkap mengenai jadwal keberangkatan terbaru armada Tunggal Jaya Transport untuk semua rute.',
                'content' => '<p>Kami terus berkomitmen memberikan pelayanan terbaik dengan rute dan armada yang terawat. Cek jadwal terbaru kami untuk merencanakan perjalanan Anda dengan nyaman.</p><p>Tunggal Jaya Transport menyediakan berbagai pilihan jam keberangkatan dari pagi hingga malam hari, memastikan fleksibilitas bagi para penumpang setia kami.</p>',
            ],
            [
                'title' => 'Jelajahi Rute & Armada Kelas Premium',
                'slug' => 'rute-dan-armada-premium',
                'image_file' => 'bentas02.webp',
                'excerpt' => 'Nikmati kenyamanan perjalanan dengan armada premium kami yang dilengkapi fasilitas terbaik di kelasnya.',
                'content' => '<p>Rasakan sensasi perjalanan berbeda dengan armada Executive dan Business Class kami. Dilengkapi dengan kursi ergonomis, leg-rest luas, dan hiburan audio-video on demand.</p><p>Setiap armada kami menjalani perawatan rutin untuk menjamin keamanan dan kenyamanan Anda selama di perjalanan.</p>',
            ],
            [
                'title' => 'Promo Spesial & Penawaran Menarik',
                'slug' => 'promo-spesial-penawaran-menarik',
                'image_file' => 'bentas03.webp',
                'excerpt' => 'Jangan lewatkan berbagai promo menarik dan diskon khusus untuk pembelian tiket melalui website.',
                'content' => '<p>Dapatkan harga spesial untuk pemesanan lebih awal atau rute tertentu. Gunakan kode promo yang tersedia untuk mendapatkan potongan harga langsung.</p><p>Ikuti juga media sosial kami untuk update promo kilat dan penawaran eksklusif lainnya yang sayang untuk dilewatkan.</p>',
            ],
        ];

        foreach ($articles as $data) {
            // Check if article with this slug exists to avoid duplicates
            if (NewsArticle::where('slug', $data['slug'])->exists()) {
                continue;
            }

            // Create Article
            $article = NewsArticle::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'category_id' => $category->id,
                'author_id' => $author->id,
                'is_published' => true,
                'published_at' => now(),
            ]);

            // Attach Image (Handle Spatie Media Library)
            $sourcePath = public_path('img/poster/' . $data['image_file']);
            
            if (File::exists($sourcePath)) {
                // We copy to a temp location first because addMedia moves/deletes the source usually?
                // Actually preservingOriginal() keeps it, but safer to work with a copy or just let Spatie handle it.
                // Best practice: direct addMedia from path and preservingOriginal.
                
                try {
                    $article->addMedia($sourcePath)
                        ->preservingOriginal()
                        ->toMediaCollection('cover');
                        
                    $this->command->info("Created Article: {$data['title']}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to attach media for {$data['title']}: " . $e->getMessage());
                }
            } else {
                $this->command->warn("Image not found: {$sourcePath}");
            }
        }
    }
}
