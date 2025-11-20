# Illuminate\Database\QueryException - Internal Server Error
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'laravel_apapesan_db.bookings' doesn't exist (Connection: mysql, SQL: select * from `bookings` where `bookings`.`id` = 33 limit 1)

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:824
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:778
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Connection.php:397
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3188
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3173
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3763
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3172
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:887
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:869
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Concerns\BuildsQueries.php:366
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:553
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:600
12 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:480
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
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

GET /booking/success/33

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **cache-control**: max-age=0
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/booking/success/33
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IlpHRkJtQmVwZW5VaW0rdVpZRWlBYkE9PSIsInZhbHVlIjoiaTdIWnN0aDJnaEZZSEdrbVQ5dTFYZEIvZE9zdlIrWGZCL0F2cE1xSTI0MkM0RmN4bURRZHRJL1g0blU2eXNBejg2N0twaDREMXhKQ0h4MVZCdVVEVWhxeFByekJoWnY5L0ZEWHQwK1YvbDlVK3BBazh0Z0R6dHJUR2ZxVUpmVG9XN3dINUVhSzJRcEl1Sk9hb3dPY0dDeGVGY2pSQ0Y3MUJiWkZ1K2lZWnk3bDlNcm5DUEhQOXlpVXZHWFdQQVQzY0twKzZ3K2xOdnFuSDd6OHhEK0MzMm1XN3FZdkRvdExlZCsxZHRteVM4cz0iLCJtYWMiOiIxOGUyYmZjMGQzOWRkMTIwMzNjYzBlMWE1MjJiZDFkNTQwZjQwZmY5NTE4ZmFkY2E4MDllZjliYjg1YmU1ZTQ4IiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IjhLZmluMkhvWDBySE9rTUxySkJQWWc9PSIsInZhbHVlIjoiYWNuUE1OTGl0YUlVQ2JPZHNOL2lHeFhkSlU2Sm0vZmFiRlBYUmZwVk9HSXE4cUF4c1BpYm1NYXE2SFJsYnYxVktFM0ZGd0ZDUkMyZEpDamZHWGwvelFPdG9Ga2J2K1J2Rkc2cDVVRFdiT0FJQmtwQ2lRTmJkYjArVENTV3pjc1AiLCJtYWMiOiJiNDc4Y2YxMDk4MTZhMTM0Y2RkYzdiN2Q3MTU0Y2YzZWU1NzBhOWIzZmJmZTU3YWYxNmM0MjZlZjBkN2FmM2YyIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6IjBEb2JYMGllOFlIMk5XczMvSzVyelE9PSIsInZhbHVlIjoiaHIrRTNPNkE0bG90akg3V3B4QStSb05JQkc1eG1Fd05PZkNBb0wvWERUaFgwOWpTWWxzTnl6UHFrMnVYNnFoa1d5SDlGV2Q5cnVoa0d5SC9WV00wNjI1QytLejI2Vm1mS0xkV3BwQnV1czFuREZ2MEM1cHRlY2hyYThZY2dYdzIiLCJtYWMiOiJlODdhOWM3MjVkYjcwYmI3NDg4YTVhY2RkZDNhYWQ4YWZlOTA5MmEyYzBlMDYwN2MwMTUzY2FiZGQzMDVkNmQzIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@success
route name: frontend.booking.success
middleware: web

## Route Parameters

{
    "id": "33"
}

## Database Queries

* mysql - select * from `sessions` where `id` = 'AABbsL2PZ64uWPB6OtTRudJdRrYJ3rm95gh8He6Y' limit 1 (17.58 ms)
