# BadMethodCallException - Internal Server Error
Call to undefined method App\Models\Schedule::getNextAvailableDate()

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Support\Traits\ForwardsCalls.php:67
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Support\Traits\ForwardsCalls.php:36
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:2540
3 - D:\laragon\www\TunggalJayaTransport\app\Models\Schedule.php:97
4 - D:\laragon\www\TunggalJayaTransport\app\Models\Schedule.php:142
5 - D:\laragon\www\TunggalJayaTransport\app\Models\Schedule.php:175
6 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:141
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Collections\Arr.php:1158
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Collections\Collection.php:415
9 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:139
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

GET /booking/schedules

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **cache-control**: max-age=0
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/booking/schedules?origin=&destination=&date=2025-09-27
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6ImkwNjFZMWJFUVFFM3pPeGh3ekViUHc9PSIsInZhbHVlIjoiUlNBeHk5aTFkZEZ2U2RlM3lVcGdLcmRCVk5KUGdkcHNwVXJRTzZWMnJ6aFk2elJmMjBMeGh6Z08zbklGN3NmVVR6MTljbFMxT1NUcjJjUkFHbzcxVVlmTlZ0cUd2T0d1eTFnOG96UkZ0ZStGNk04VDIyYjhWT1MwYXg5Sk9zV2NybjVmVGtHTXY4NktOUWJ6OVZjcWVwT0NpWWtTbE00aHlubnh3T0IxeVR4S1dvc0ppRmRWbE14cFdNbllvMWViRFlLbjRhWFFFQnVIRldNZnMwalZEWjdTbFgvcGNkejR5blRYMlZXVmJSWT0iLCJtYWMiOiJkNjhhNTkwMjZmNGMyZDJhMTQwZDU2ODMxYzFhNmViMzNiYTY0OTA0NTQyY2E3NGYwNDJjNTNjMGRiM2U4NzJiIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6ImVlOENTOU5EOXRHMWVHa29SRHUzR3c9PSIsInZhbHVlIjoiY2hiYnpoK3FqbXN3Yk1qYktzcE5Vb0J6QkZkN1RGd1ovY2l0WVBLbUVoWDAwMVZTTGZycWlXdm5pbGExOVJZSFpBZFdna2pUN1hUOVErMDlDbGZUSXc3TkNXS29mNC9Jcjk4OUZwTnJPQ2ZyYVJtdVVISzl4KzJuVXdhSm5RMUsiLCJtYWMiOiJkZmM2MTI5ZGI3YzRmMzQ4NTBkZDFkNmI0ZGU4NmZjYzM2YmM0NWFmODMwNGU1MTg5YTgzMTM0Y2Y0NGQwYmZiIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IkZ2cHJwUEhlZDRsNC9FOXg2SkVnWWc9PSIsInZhbHVlIjoiRkpvYm9GWmtGaFdNOFFLeG9aMEdJNEVNWjJnU2VZL203MWlYYTMvMEI3OXlpSWp6SGJDRDdNQ0N4dHZJVjdKcFkxc2dYZ2txbXJjSHZuaXhxbkFrUXV5Q0tPdTRVYWVrakJ4MmRHeDd0L3VMS2Y5Z1VpRkZqcyszaWZ5UjVGamciLCJtYWMiOiJlOTkwMDRlYjVkNGRkMmZkNTNmMzRjMDJjMjJmZTQ4OGE4MDgzZjgxMTdmYjUxNWM2OTVlOWNmYWM2YWMxNDAyIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6InhpOGdXcmtXRlRBN3JXb3lsb1FzWmc9PSIsInZhbHVlIjoiTWdrQmkva3dzQWJzVUVURFZmcUZMaGtIdTVRV2d5ZUZ0Rk9GUlZySHMwdmgwZXBrT3N4RXB1MWg3N2M2ZHR6aWlIU3E0L3hqSWgybVI2ZFMyT0Q5OG1aOXY2WnpqYVdKbWkyNWkyQkFSdm9kYzdtaDRPdnBWQ2YvWlJUZ3k3YW4iLCJtYWMiOiI4ZDRjNjhhNWY4Njk3NjY1NmI0OTU0NWVhNWQ3YmE5NWY1NTA5YWZlNjVmMDRmMmRhNDJiYjI1YTc4YTQ2MWJjIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@schedules
route name: frontend.booking.schedules
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = 'xJaKGy2KIspq8WsqC0QjnAFZLYDWzrfq8DkT1tn9' limit 1 (22.43 ms)
* mysql - select `origin` from `routes` (0.87 ms)
* mysql - select `destination` from `routes` (0.72 ms)
* mysql - select * from `schedules` where `status` = 'active' and exists (select * from `buses` where `schedules`.`bus_id` = `buses`.`id`) and exists (select * from `routes` where `schedules`.`route_id` = `routes`.`id`) (1.43 ms)
* mysql - select * from `routes` where `routes`.`id` in (1, 2, 3) (1.02 ms)
* mysql - select * from `buses` where `buses`.`id` in (1) (0.79 ms)
* mysql - select sum(`number_of_seats`) as aggregate from `bookings` where `bookings`.`schedule_id` = 10 and `bookings`.`schedule_id` is not null and `booking_status` = 'confirmed' and `payment_status` = 'paid' (1.16 ms)
* mysql - select sum(`number_of_seats`) as aggregate from `bookings` where `bookings`.`schedule_id` = 15 and `bookings`.`schedule_id` is not null and `booking_status` = 'confirmed' and `payment_status` = 'paid' (0.94 ms)
* mysql - select * from `users` where `id` = 1 limit 1 (1.02 ms)
