## TODO LIST

-- Frontend fix bagian home page belum sesuai dengan backend
-- fix news crud belum ada buat upload foto news cover nya

-- fix setting error

# Illuminate\Contracts\Container\BindingResolutionException - Internal Server Error

Target class [SettingController] does not exist.

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Container\Container.php:1163
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Container\Container.php:972
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1078
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Container\Container.php:903
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1058
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:286
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:266
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\EnsureEmailIsVerified.php:41
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
55 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
56 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

GET /admin/settings

## Headers

-   **host**: tunggaljayatransport.test
-   **connection**: keep-alive
-   **upgrade-insecure-requests**: 1
-   **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
-   **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,_/_;q=0.8,application/signed-exchange;v=b3;q=0.7
-   **referer**: http://tunggaljayatransport.test/admin/reports
-   **accept-encoding**: gzip, deflate
-   **accept-language**: en-US,en;q=0.9,id;q=0.8
-   **cookie**: XSRF-TOKEN=eyJpdiI6IkdqYnhwUXc4aXI0NDVGQk5OYUt2aUE9PSIsInZhbHVlIjoiVytlNURXVVVTSlBjT2cyb0l4aHg3NVBicTZxL1JTNHJtbzduTG50cENwMmlkRE9ncHpaVDRUTkdhUFprRy9sUU5uZ1dTZ0FwUzFxbVZxNllUK0UrdmxhU3VmQlBESHJ4bGZDL1hsdzBzQWZUWkh4STltdllGdGxneUtucVZtOG4iLCJtYWMiOiI1OWE3NTBlZDMwM2Y0OWNmYmUwYjdmZGY4MWQ1ZDZjM2M0MjNiM2E5ZDMxNTZkYzJlYTBhMzFhMTM3NzU3YmEyIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IlZvOEVQcXRyekdsUitia2hDR2pWWUE9PSIsInZhbHVlIjoiY2RmZngwNHBqdEJGQnBuaW84SzU3ZjNONU9kaklWQko1QmRVUlB6OXYzNE95Q29aT0JZYnViTVhQRmI4U1luMWVHQmUweWxOTHVteWIrYVRDK1lCQzhpY0k5ZW5aS253THhOYWNoQmplK0RyQjdXMmM2NmRIZUZPVGtub3VRdFkiLCJtYWMiOiI3NzdmZjcyYjRmZTczZWQ1NDBlZTdiYmUyYjcyZjk1ZTdlYjMzN2I1YWM2ZjkwZWU1NjI0ODdmM2ViMzZmYTA4IiwidGFnIjoiIn0%3D

## Route Context

controller: SettingController@index
route name: admin.settings.index
middleware: web, auth, verified

## Route Parameters

No route parameter data available.

## Database Queries

-   mysql - select \* from `sessions` where `id` = 'CTnGPGmloXg2sYcsaBWujZdjKupuJXd0YOcZ0UMS' limit 1 (21.62 ms)
-   mysql - select \* from `users` where `id` = 1 limit 1 (1.33 ms)
