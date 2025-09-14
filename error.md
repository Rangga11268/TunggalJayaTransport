# Illuminate\Database\UniqueConstraintViolationException - Internal Server Error
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'test' for key 'news_articles.news_articles_slug_unique' (Connection: mysql, SQL: insert into `news_articles` (`title`, `slug`, `content`, `excerpt`, `category_id`, `is_published`, `author_id`, `updated_at`, `created_at`) values (test, test, 123, 123, 1, 0, 1, 2025-09-14 09:29:39, 2025-09-14 09:29:39))

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:819
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:778
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:42
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Processors\MySqlProcessor.php:35
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3853
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:2220
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1436
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1401
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1240
9 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Admin\NewsController.php:49
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\EnsureEmailIsVerified.php:41
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
55 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
56 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
57 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
58 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
59 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
60 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
61 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

POST /admin/news

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **content-length**: 96
* **cache-control**: max-age=0
* **origin**: http://tunggaljayatransport.test
* **content-type**: application/x-www-form-urlencoded
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/admin/news/create
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: XSRF-TOKEN=eyJpdiI6Imw0OWpHYk9UQ3JYMlNKRU9KdmVtcEE9PSIsInZhbHVlIjoiWVNLL2ZyenlJU3lVdlFvd3hOY016TXdsNVllbkVhWFI2MVJocnViYTZyVmMyK2l0clVPNDUrU002VnZyVWJNazA2ZFdjU3hoZ1phYkVzcGNIU2IzdzVHQ2pYaFZkSWRxSWp4dExVREJZckVtbmtTVEd6L2hqbnptWnhCOGd1VG8iLCJtYWMiOiJjODlkMDE4NWRlNzg2N2ViOGY3YTBlMjc0MTEzNGM3MjkzZDExNTIxM2QwYjNhYTc2ZjlmMDU2MTkwYzFhZDJhIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IlVwQmcva1hzcXBIdkNTOEJoU0F3NEE9PSIsInZhbHVlIjoicitFTi90am1qQmpGcHp0SXU0dHYzcERuNFVvMXVZOTJZY2VBYnE3bHQ4R1ZENVJ4alVPT1JJbTg5MXVsK2JLRmRDcEhoOUZzSjNkL2h5SVFldGVpR0NlVVNZVWo0dmg3NFlhdFpuSlJYb1RQUmloTk5TU2Jjc3lXSmRzc29Pa0IiLCJtYWMiOiI4YTYyODE0OWVmYzQxODU3MjdjY2E0NzcxMDI2ODNlMDBkN2RkOTg4MTBlMGI0MGJiYzFkZjY2OGVmODllOTIzIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Admin\NewsController@store
route name: admin.news.store
middleware: web, auth, verified

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'bW6MdsH6b9DcbaWcOJUTEtnhdWKmVh3d8idgGyye' limit 1 (5.87 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.43 ms)
* mysql - select count(*) as aggregate from `categories` where `id` = '1' (1.3 ms)
