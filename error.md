# ErrorException - Internal Server Error

Undefined variable $registeredUsers

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\resources\views\admin\dashboard.blade.php:39
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:123
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:124
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php:57
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php:76
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:208
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:191
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:160
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Response.php:78
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Response.php:34
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:939
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:906
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
14 - D:\laragon\www\TunggalJayaTransport\app\Http\Middleware\RoleMiddleware.php:30
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
55 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
56 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
57 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
58 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
59 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
60 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

GET /admin/dashboard

## Headers

-   **host**: tunggaljayatransport.test
-   **connection**: keep-alive
-   **cache-control**: max-age=0
-   **upgrade-insecure-requests**: 1
-   **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
-   **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,_/_;q=0.8,application/signed-exchange;v=b3;q=0.7
-   **referer**: http://tunggaljayatransport.test/admin/dashboard
-   **accept-encoding**: gzip, deflate
-   **accept-language**: en-US,en;q=0.9,id;q=0.8
-   **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6ImkwNjFZMWJFUVFFM3pPeGh3ekViUHc9PSIsInZhbHVlIjoiUlNBeHk5aTFkZEZ2U2RlM3lVcGdLcmRCVk5KUGdkcHNwVXJRTzZWMnJ6aFk2elJmMjBMeGh6Z08zbklGN3NmVVR6MTljbFMxT1NUcjJjUkFHbzcxVVlmTlZ0cUd2T0d1eTFnOG96UkZ0ZStGNk04VDIyYjhWT1MwYXg5Sk9zV2NybjVmVGtHTXY4NktOUWJ6OVZjcWVwT0NpWWtTbE00aHlubnh3T0IxeVR4S1dvc0ppRmRWbE14cFdNbllvMWViRFlLbjRhWFFFQnVIRldNZnMwalZEWjdTbFgvcGNkejR5blRYMlZXVmJSWT0iLCJtYWMiOiJkNjhhNTkwMjZmNGMyZDJhMTQwZDU2ODMxYzFhNmViMzNiYTY0OTA0NTQyY2E3NGYwNDJjNTNjMGRiM2U4NzJiIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6Ik5mUnJCdnFvVUEreW1nSU9KSFRCQVE9PSIsInZhbHVlIjoieDc0Q0dYZ3UwZG5BRFdaMDNXVDBIa1pjL3N2TDJzUnZVMTZNdnV5RDVMKzlGcDc4aGJSSHkxK0ljbVFsODMwQWJyT0M1MVJXUytVYkxaS0hEODlZRlNwbFJzZlJqNjEwK2FGdlZFbkpDdUtXWlZGY2RMTUFadlJSb1NaLzlaUXMiLCJtYWMiOiJkNDIyMGU0MDcxMTIwZTU1YjgxNjc1MWU2ZmRmZDVlNDA0ZDkxNTczZDQ4Mzg2MjhhMjJiNzYzOGU3ZWNkYWIyIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6Im83S20vZUVxYXBBNGtyK0JnVzYxb2c9PSIsInZhbHVlIjoiQWJiR0VncnBtTUdzN1hiTmY5UXJicnFFTzZXYWdBMUJ4RXpnUU01ZjY2L1NIZDF2Z0Rpck9sTTROdnZsYUdwWWtEUi92REVhVDgzaWJzM0VMMWZTWmw4K01qdkhKdkpvZE14VlAzWWx1b2tyNnFIcUEyYTJIVUNYMWNIQU1EaVUiLCJtYWMiOiJlOTY1NmE1MzM4YmYxNTQxNzhjYzJiZDNhZGMwZTg2ZDdiNzBjYzI3ZWU0MTQ1MTkyNjEwZGZjMzI4OTczMjVmIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6InZJeWdEcEVpeEJyTGtXWDNQVnlvaWc9PSIsInZhbHVlIjoieDRJSkU3VFc3LzB4bm1CVGVyT0xRU3d5Wmd3L3N5UDNWb3N5YS9RSFdvSk1uMGljQ0dOWHp1UzhsWlBzcTltV1JCOTJUMis2L1VOTC85NGFod09VSnNGV2VmekF5dFhkN0Q0UkgrR3Y5TDNTbUtkcm9raTlQdjF1NWlFbzZBeEsiLCJtYWMiOiI1M2RlYWUwZDdkOGFiNDZjNTEzZGRjMjJkNWVlNDA0NjZjYThjMTA5ZGJjOGY1NDUwM2NhNTNhMzFjZDI2ODYzIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Admin\DashboardController@index
route name: admin.dashboard
middleware: web, auth, role:admin,schedule_manager

## Route Parameters

No route parameter data available.

## Database Queries

-   mysql - select \* from `sessions` where `id` = 'WfYaaGKnpHIAdamkekppDI19dfblpNKTqmO8BPxg' limit 1 (22.88 ms)
-   mysql - select \* from `users` where `id` = 1 limit 1 (1.42 ms)
-   mysql - select `roles`.\*, `model_has_roles`.`model_id` as `pivot_model_id`, `model_has_roles`.`role_id` as `pivot_role_id`, `model_has_roles`.`model_type` as `pivot_model_type` from `roles` inner join `model_has_roles` on `roles`.`id` = `model_has_roles`.`role_id` where `model_has_roles`.`model_id` in (1) and `model_has_roles`.`model_type` = 'App\Models\User' (1.46 ms)
-   mysql - select count(\*) as aggregate from `bookings` (2.55 ms)
-   mysql - select sum(`total_price`) as aggregate from `bookings` where `payment_status` = 'paid' (0.99 ms)
-   mysql - select count(\*) as aggregate from `schedules` (2.57 ms)
-   mysql - select count(\*) as aggregate from `users` (2.47 ms)
-   mysql - select \* from `bookings` order by `created_at` desc limit 5 (1.17 ms)
-   mysql - select \* from `schedules` where `schedules`.`id` in (10) (1.3 ms)
-   mysql - select \* from `routes` where `routes`.`id` in (1) (1.21 ms)
-   mysql - select \* from `users` where `users`.`id` in (1) (1.18 ms)
-   mysql - select \* from `schedules` where `departure_time` > '2025-09-19 12:46:48' order by `departure_time` asc limit 5 (1.29 ms)
-   mysql - select \* from `routes` where `routes`.`id` in (1) (1.08 ms)
-   mysql - select \* from `buses` where `buses`.`id` in (1) (1.02 ms)
