# Illuminate\Database\QueryException - Internal Server Error
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'payment_started_at' in 'field list' (Connection: mysql, SQL: insert into `bookings` (`user_id`, `schedule_id`, `passenger_name`, `passenger_email`, `passenger_phone`, `seat_numbers`, `number_of_seats`, `total_price`, `booking_code`, `payment_status`, `booking_status`, `payment_started_at`, `updated_at`, `created_at`) values (1, 51, Darell Rangga, tencent.id011@gmail.com, 08978638973, ?, 1, 120000, BK68CD1FE147873, pending, confirmed, 2025-09-19 09:18:25, 2025-09-19 09:18:25, 2025-09-19 09:18:25))

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
9 - D:\laragon\www\TunggalJayaTransport\app\Models\Booking.php:93
10 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:182
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
33 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
34 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
35 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
36 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
37 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
38 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
39 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
40 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
41 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
42 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
43 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
44 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
45 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
46 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
47 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
48 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
49 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
50 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
51 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
52 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
53 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
54 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
55 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
56 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
57 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
58 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

POST /booking

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **content-length**: 188
* **cache-control**: max-age=0
* **origin**: http://tunggaljayatransport.test
* **content-type**: application/x-www-form-urlencoded
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/booking/51
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IjdMZ1RGalhsUWJIKy9DZEYwRnZ1L0E9PSIsInZhbHVlIjoiSlZHRzI4UFhyempEbzhCWjM0c2thaVVhVkRna2tOTEE0YTMzb3dvZWRXdDNnZlJCY2twbDc3WUpTYnhBMXJFQldXbTVRdFh3Z3NZQzlKRmdZSFU5cnJ2emVHcUpHUUVERFdxWTd1TURtR3lUSjRXN0NvZjRSb3BpelQ3VmlvaElyVUZ6MlRzQmlOajBpT1BZOXljTEhYelV0VEVaaGxwNTIvakdYai81R2VSZCtURHlJUGNraytGaXhvNUIwbGlwZGpxQ3BQeklrbEppSzMwb2FNc3dWanFYcU82NEc5NjNsWlVvMHh5dUEwcz0iLCJtYWMiOiIyM2E3OTBhMDU2MmY0YjY3NzViMWE5NjJkMjZkOWM3NDk5OGU4MDQ3NjgyMzI2NDcxOWZhMjcwMmFjMWY5YThiIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6Ik8xa3lFOEhDRUJ2VWdWdVJldldzZWc9PSIsInZhbHVlIjoiMlJTNSs5Z2xmS05jem5lc1FQQjZiKzBjb0M4bEo3cHJlWkk0NitsNXN1NUh2OHhZZ2ZaU2FsdDNvWkw3dmVaK3M1NEhUYUhXc0IzMmZCT0pOdk1HVUt6NnVPWlNNNlFwbGVHQmtuWmtrRkYzaWJIN2NwT1ZFK3ZrVC9WdkYzQ1MiLCJtYWMiOiI0YTdhZDg5YTdkMWEyMjE3MDAwODRlZTllMjJmYmJiMDYzMmJmMDUwNzJhY2ExNTQwZmM3ODA1OWU4ZDM4YzI2IiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6InlYYzVWTVJyaS9uVlpzUmJXZXF6U1E9PSIsInZhbHVlIjoiNUlEaUpKbGlyRXA5WFpMek5oSytzNHNXQmtlUzY0anZpeURMNVQwVFNCbk5leE1INEtiSEk5c1ZDWXBuTmlUaVY1cUV2ZXdMUGFZTEdZdGNQQTdKMWRFWkhqYWFoQmJJNE03emMybHQwNUxQWW9ZWDE1ejJ3WWVpMEQxRjNXUHciLCJtYWMiOiI4YzI3OGQ1ZDg3ZGQ3NDE0YjdjMTU2NGU0OTAyYjBlOWI0ODMzZDcyZTkwYzQ3MjQ2NTRhOWRhY2M3YTU4YTg3IiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@store
route name: frontend.booking.store
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'Uwvjy3h6HBB0CXM04Kx5qQAwfF8Qu1iQzdZh4G44' limit 1 (24.53 ms)
* mysql - select count(*) as aggregate from `schedules` where `id` = '51' (1.18 ms)
* mysql - select * from `schedules` where `schedules`.`id` = '51' limit 1 (0.88 ms)
* mysql - select * from `buses` where `buses`.`id` in (8) (0.86 ms)
* mysql - select sum(`number_of_seats`) as aggregate from `bookings` where `bookings`.`schedule_id` = 51 and `bookings`.`schedule_id` is not null and `booking_status` = 'confirmed' and `payment_status` = 'paid' (1.03 ms)
* mysql - select sum(`number_of_seats`) as aggregate from `bookings` where `bookings`.`schedule_id` = 51 and `bookings`.`schedule_id` is not null and `booking_status` = 'confirmed' and `payment_status` = 'paid' (0.72 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.04 ms)
* mysql - select * from `schedules` where `schedules`.`id` = 51 limit 1 (0.94 ms)
* mysql - select * from `buses` where `buses`.`id` = 8 limit 1 (0.73 ms)
