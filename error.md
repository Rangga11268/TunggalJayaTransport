# Error - Internal Server Error
Class "App\Http\Controllers\Admin\Carbon" not found

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Admin\ScheduleController.php:261
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
6 - D:\laragon\www\TunggalJayaTransport\app\Http\Middleware\RoleMiddleware.php:30
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
52 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

POST /admin/schedules/26/create-next-day

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **content-length**: 47
* **cache-control**: max-age=0
* **origin**: http://tunggaljayatransport.test
* **content-type**: application/x-www-form-urlencoded
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/admin/schedules?page=1
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IlJqaytYbitGNW5vOEJPUXJDcURCY0E9PSIsInZhbHVlIjoiQVFmUFZDQTRYUzZEWGo4eUZkUzF0cFI4S25YVU5naWcwcXRVYi92NlZ3bVpwNkQwN1BCOVQxbUxpSEt1RkdNeHlhUkJ0eVcwL0dlT0c5eTJSbUdkd1hsRmFHTTNHaDhwTGlITTVNbzRIZ0VZY3NxSllNRUVRbDBDL2RTZDNoaGQvTVU0WmprSjl1ZnFWUGlHeUhjUUQzZWJHWWRHTG56akZzS0ZhWGU5Y1ZsaDhiak5XUEEwbWlBNmlPbHBBcDYwekNmc3pxS1pSb3FPemt5LzBlOXYxOTErbWJaNDZ0M1BsTFdNaEhUOGFCdz0iLCJtYWMiOiJmNmQ3MWM1ZTI0MmZkNDY1Y2I4YTY3ZmMxNGNiMGNkNTExYjdmMDg3NmI3ZDU3NjI3OTY0NzM1OWM1MDEwNjA2IiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IkhzajdIRldIQTU3elVtSFNPY21ZcXc9PSIsInZhbHVlIjoicTdmUlJRRmIyamxyNElTWHp5KzlzMXcvemc5cmltbEtiY0J5V1pVWnNXMnJIZTE0cFFSYVMwWU1xc3JteEJ0WjY2ZHMxSEZ4WjVqcW44L29peE01VlRoZ09PRlFydjNDc2FyK0lXSVJOSkJ1VHo1OTRxdUxmYnY0QzVTM3NsSG0iLCJtYWMiOiJmZDc1Yjg1ODdlZmIxYjc4YjRkOWU1MzliYmQ4ZWMzZmNmMjk4MjE2Y2FiMzRkOTZhYjYyNTEzNzNiNDcyNjljIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6IkovUktnYTNtZmxsNUwyOHJoT2hBS1E9PSIsInZhbHVlIjoiTzZHdDBLLzk2M2p4UFlMNGpsYnpXTlAzZ2k2a2VpWEFwaWxGWEQxY1lYTGljaEt2Rmp3Vk9GanliYjU4cFBGUHRCN1JoajU5eGUvdGUydHRyd3RLQ2piSURRWVJrMmZhWC9EZzF2S3A0TVppMnJFQXVQTGttYjV4cjN0K3QzNisiLCJtYWMiOiI2YWU1ZTlhNTQ2YzEzMjRiOWU1ZGFkNGJkZDBhZWUyYjdhMmM0ZGZiYzYxMmE3MDljY2I2NWYzOWJjOGYzMWEwIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Admin\ScheduleController@createNextDaySchedule
route name: admin.schedules.create-next-day
middleware: web, auth, role:admin,schedule_manager

## Route Parameters

{
    "schedule": "26"
}

## Database Queries

* mysql - select * from `sessions` where `id` = '5wkH9Zg3xft6UWTmNVC9MpCm5OpUkB9hIw7ZEPPB' limit 1 (15.4 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (0.98 ms)
* mysql - select `roles`.*, `model_has_roles`.`model_id` as `pivot_model_id`, `model_has_roles`.`role_id` as `pivot_role_id`, `model_has_roles`.`model_type` as `pivot_model_type` from `roles` inner join `model_has_roles` on `roles`.`id` = `model_has_roles`.`role_id` where `model_has_roles`.`model_id` in (1) and `model_has_roles`.`model_type` = 'App\Models\User' (1.04 ms)
* mysql - select * from `schedules` where `schedules`.`id` = '26' limit 1 (1.15 ms)
