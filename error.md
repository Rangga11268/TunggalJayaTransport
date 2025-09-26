# Illuminate\Database\Eloquent\RelationNotFoundException - Internal Server Error
Call to undefined relationship [busType] on model [App\Models\Bus].

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\RelationNotFoundException.php:35
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:954
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\Relation.php:119
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:950
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:924
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:904
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:870
7 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\HomeController.php:29
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
55 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

GET /

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **cache-control**: max-age=0
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6Img3cXgxYkI1Q2t5cFI1TUJQNjJveGc9PSIsInZhbHVlIjoiQWh4bWxQTkExSE1HTzVkbU1Dd3pSVmlDOE1pcHFQN1lIZWZobk9URGxRQmZSQmZHOHFISHdxcnc3M3NiTXl0cHBSOCs3b3c4blVsZ0d0cEwyNzVtWmM4dFcwQkdkMTFPUnJRWit1NEJaUTdGYzJyR0VZR2JSR0RORkJNRUFxNExzMFFCNGtKZ01hTnVoTHNPdWd0Q3phclFaOGs2cmNnbXROT0RFTENTVnRaVStxWFE2WWdyTnZzZG5senBKM1kyRFcxN29zTmZMbGIwSXp4dDQ0cGI2enE4K21qMklXL1BKNmdoMWY2Z1VZMD0iLCJtYWMiOiJkZDVjYjUzYWFhMTgxMWNkYjczYmQzM2I2MDQwYTFiNjQxOGU5YWYyNjZlMGUzZTk5NmI2YTEwYTY1YjhjNzBjIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6ImFmVm1EaFdibHBubWZEMlV2R0VkYVE9PSIsInZhbHVlIjoiUFdIaFVXRGRCMURPb3Nvc0tHYjYyako4L2l1am1wNjY5Nnd5d2lSa2tCZDE3UkwySWRxSm84ZDJveWtrNnlhWk9VUGlzd1IxZ1N3OWhOYUhNYXUzSTNsUUZaTlRRUlkxTzF5LzBINnB1TXR2NlJrcnhyTEtWbFRSVnh3TXV1WFgiLCJtYWMiOiIxYTJiNGE5YWVkMmJkZjAzMzI5YWZjMDEyNmU0NGE1ZmVkY2IzYjIyYjYzOWZhOWM1ZDhiMTY5MTU5MzU4ZmQ5IiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6InhRTGZ3MWg5bXVVSlovbUsvRjRsZXc9PSIsInZhbHVlIjoiNG92RXY0UkVWQ1VqQzgrVUQyUXptN1RURjZkVldtTDE3ZE5WdUNGckd0WFZLbjhWOWxWVnJXRWRHcStjL1owMTZvWDVXNHl2N2l5dzJRMWs5Qyt4M3FUNWcwOVNwbGZRc3V4S3h3bHg3ZlQvWDJhNXpmeTBWMUlZUzN5NFgvMVEiLCJtYWMiOiJlZmYzYmEyYTNjNTkxZWU1NzhkMTc1YTI4Njc1NGYwY2M2ZmQ0MzY4OWZkNDI0ZDExM2Y4OGFkODdlZTM3ODE1IiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\HomeController@index
route name: frontend.home
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'rjyoBfRcKrBOzGUcCDjMb58U3b4FFDY77cH3LpWA' limit 1 (5.57 ms)
* mysql - select * from `routes` limit 3 (1.05 ms)
* mysql - select * from `news_articles` where `is_published` = 1 order by `created_at` desc limit 3 (1.28 ms)
* mysql - select count(*) as aggregate from `buses` (2.17 ms)
* mysql - select * from `buses` limit 6 (0.97 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.04 ms)
