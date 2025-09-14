// user crud error

# Spatie\Permission\Exceptions\RoleDoesNotExist - Internal Server Error

There is no role named `2` for guard `web`.

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\spatie\laravel-permission\src\Exceptions\RoleDoesNotExist.php:11
1 - D:\laragon\www\TunggalJayaTransport\vendor\spatie\laravel-permission\src\Models\Role.php:105
2 - D:\laragon\www\TunggalJayaTransport\vendor\spatie\laravel-permission\src\Traits\HasRoles.php:412
3 - D:\laragon\www\TunggalJayaTransport\vendor\spatie\laravel-permission\src\Traits\HasRoles.php:131
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Collections\Traits\EnumeratesValues.php:825
5 - D:\laragon\www\TunggalJayaTransport\vendor\spatie\laravel-permission\src\Traits\HasRoles.php:126
6 - D:\laragon\www\TunggalJayaTransport\vendor\spatie\laravel-permission\src\Traits\HasRoles.php:227
7 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Admin\UserController.php:100
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\EnsureEmailIsVerified.php:41
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
55 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
56 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
57 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
58 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
59 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

PUT /admin/users/3

## Headers

-   **host**: tunggaljayatransport.test
-   **connection**: keep-alive
-   **content-length**: 157
-   **cache-control**: max-age=0
-   **origin**: http://tunggaljayatransport.test
-   **content-type**: application/x-www-form-urlencoded
-   **upgrade-insecure-requests**: 1
-   **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
-   **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,_/_;q=0.8,application/signed-exchange;v=b3;q=0.7
-   **referer**: http://tunggaljayatransport.test/admin/users/3/edit
-   **accept-encoding**: gzip, deflate
-   **accept-language**: en-US,en;q=0.9,id;q=0.8
-   **cookie**: XSRF-TOKEN=eyJpdiI6IlBLM2kzNU5VUVo1YnQvdmFLUW5sYXc9PSIsInZhbHVlIjoiWkJWSEJtRVV2QXBJQTRQem5UM1c3azl0c2MrZ1ZYUUlZSVVmcit5dmxTMVdsQ29pcmxUbEZmQVc5MGYzUVRONWlSWFQ4QUd1L09uMGJKSHhiZ1BhMmhhRy9qZkNSVVlBSHJubHFmRG1zV3NXdmpWRTc2MXBtb2QxRGpwWERQTnYiLCJtYWMiOiJmMjM2ZGQ3MzYxMzAwZWY4NDFjMTJiMDQ0NjYxZDkxNTZiYmI4MGNjYWQ2YmYzYTM2NTJjYWYzMGNlNWQ2OTY5IiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6ImZjN0lwTmcyWHhrU1R1MUpCcUdacXc9PSIsInZhbHVlIjoia3hNOGpjbkpOdWFYZjhKZUR5aXlwTlJ1WUNhZk9QS05MZmxXTWpXdFErdEoyelI4QkZQaWZCZE5YL3NuM2JyMmNrOVJTMkxaU0JoazhXcDlxdDJzNjA4cWZWdk1CMEloTEtEeEc0bENJWGFYajFscHJ5NnZoR1hSdm5KcG8yM0MiLCJtYWMiOiJkYzNhOWNkMjUzZWJhNGRkNDg3YjYxNmUyZTg0MGQ4Zjc3Yzk1M2M3OTY1MmZkOGY1OTgwYTljNTdhNjE5MGE3IiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Admin\UserController@update
route name: admin.users.update
middleware: web, auth, verified

## Route Parameters

{
"user": "3"
}

## Database Queries

-   mysql - select \* from `sessions` where `id` = 'bW6MdsH6b9DcbaWcOJUTEtnhdWKmVh3d8idgGyye' limit 1 (6.53 ms)
-   mysql - select \* from `users` where `id` = 1 limit 1 (3.19 ms)
-   mysql - select \* from `users` where `users`.`id` = '3' limit 1 (2.97 ms)
-   mysql - select count(\*) as aggregate from `users` where `email` = 'test@example.com' and `id` <> '3' (1.22 ms)
-   mysql - select count(\*) as aggregate from `roles` where `id` = '2' (1.03 ms)
-   mysql - update `users` set `password` = 'y$AGPdItp6gj6dqO5SfaPSFuCqHocTL2jcJqwuHqIsRvStSGp2kF/.q', `users`.`updated_at` = '2025-09-14 09:24:45' where `id` = 3 (9.33 ms)
-   mysql - select \* from `roles` where `name` = '2' and `guard_name` = 'web' limit 1 (1.12 ms)




// category error
# Illuminate\Contracts\Container\BindingResolutionException - Internal Server Error
Target class [CategoryController] does not exist.

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

GET /admin/categories

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/admin/bookings/create
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: XSRF-TOKEN=eyJpdiI6Ik1CZ1FwbW8zZU1FS1RkQk1FZmk3TEE9PSIsInZhbHVlIjoiMWtibzYweHZ5cXhKbVFDZEpZdU5YcGNMSHJ5K3NzV0owdlFyRmNNNVJ3MkR4bEJlNGV0T0lyd0piL01jZUhlVlNQM3V0MnQwVVMyK2Fwam8xN0xJTWl1cTd2YXdLeFBDSUpWQmU4TytPUXRpdGZ4VHk1eUkxTHgvb2kwZm1qZlYiLCJtYWMiOiI0ZTFmZThmNjlkZDFiYTYwODAxYmJhYTcyMjU4YjI2YzViZWNhMzA3NTAyNjI0ZWM0MzFhYTE4MTk3ZjQzZDAzIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IlBZVTZ0QTBmbGN4U1lCcVBRamR4cUE9PSIsInZhbHVlIjoieXBiVUoxQVRJTWcyY2dTdllUdkxGQVVrRGdsdjVBTzk3aDIwUHRJVDlWS2VJMVNzV0dxYzYzZWFrSWwwSklEcEdpdldjbWtWRzZyTUoyZGRDYWpNd2RxYWN3RDVZeSsvMHlicjVFeUNOUnpsTTgyR0RBT2RlZk9FbHdMSkFPY1IiLCJtYWMiOiJjZmY0ZWJkMGM2OGJlNTBhMjlmMzk2ODE0NGE4NGVkYjVhYWM5N2QxYzIwYmJkMjIxMGZlYTllODc2OTQ3MTBkIiwidGFnIjoiIn0%3D

## Route Context

controller: CategoryController@index
route name: admin.categories.index
middleware: web, auth, verified

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'bW6MdsH6b9DcbaWcOJUTEtnhdWKmVh3d8idgGyye' limit 1 (17.27 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.25 ms)
