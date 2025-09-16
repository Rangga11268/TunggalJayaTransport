# Illuminate\Database\QueryException - Internal Server Error
SQLSTATE[HY000]: General error: 1364 Field 'seat_number' doesn't have a default value (Connection: mysql, SQL: insert into `bookings` (`user_id`, `schedule_id`, `passenger_name`, `passenger_email`, `passenger_phone`, `seat_numbers`, `total_price`, `booking_code`, `payment_status`, `booking_status`, `updated_at`, `created_at`) values (1, 3, admin, admin@gmail.com, 08978676976, ?, 240000, BK68C93234BA892, pending, confirmed, 2025-09-16 09:47:32, 2025-09-16 09:47:32))

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:824
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:778
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:42
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Processors\MySqlProcessor.php:35
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3853
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:2220
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1436
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1401
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:1240
9 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:81
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
55 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
56 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
57 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

POST /booking

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **content-length**: 171
* **cache-control**: max-age=0
* **origin**: http://tunggaljayatransport.test
* **content-type**: application/x-www-form-urlencoded
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/booking/3
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: XSRF-TOKEN=eyJpdiI6IllqWkhyenNLVjZ5cGFPcmhxZmJlcVE9PSIsInZhbHVlIjoiNG1jVGNQK2RXZkxsMFRlRjhDUFB4TTBzK2F6TXYweTNieUtpSG54ZXk3WmRFa1JtSkVRNnAvZXhlYlptd2pCbHVhQUVERk9odHpzNHdMaUU5NjF1c2YrNlFGM2RXNlBqTGZvOEY1Zk1DQmJBTS9oSjdubU4rVHlrZDM2RHZuSk0iLCJtYWMiOiI1NzNhZGFkODFhNTI0NTE1OTIzNDEzMGUxNjdhMmY5MjA3ZDkyNzEwYWU1OGVmNGRhODUzNDI1YjQ4NDcyYmEwIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6ImdWN2RoMWM2LzE2Yjl0WGtCSVc4V2c9PSIsInZhbHVlIjoiRlA0K2g4dkNDWlFuVXlUR1JySkRLV21tZnJpbzlxYjZCTTVkZGZTa0oyZmVuVTZ3bk11MEpyWXVxbTZ5ZlBYVDY5c0dZWDNPem9PMWJ1L1BEZ2FJLzdoQ25hRTQ3YUhlbms0cjhIVGt3bGFaQXlJU3FKc0YrQjdiREZnYmgzaGoiLCJtYWMiOiJmZDA3ZDMyZTA4NDNhOGNlZWI0NGI1NDQ2OGY5ZjRiZGI4MjhjZTFiZjI2MDgzMzI3ZTQxMTBhN2VjM2FjYjc1IiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@store
route name: frontend.booking.store
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'CRTwYvo1cUnEwc2h2qR4eT4fFWsm6AXVUvXSyIWv' limit 1 (6.88 ms)
* mysql - select count(*) as aggregate from `schedules` where `id` = '3' (1.16 ms)
* mysql - select * from `schedules` where `schedules`.`id` = '3' limit 1 (1.29 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.26 ms)
