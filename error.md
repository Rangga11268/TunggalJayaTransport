# ParseError - Internal Server Error
syntax error, unexpected token "return", expecting "function"

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:225
1 - D:\laragon\www\TunggalJayaTransport\vendor\composer\ClassLoader.php:427
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:1119
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:1056
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:834
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:816
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
9 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
10 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
11 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
18 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
20 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:48
22 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
24 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
26 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
28 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
30 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
31 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
32 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
33 - D:\laragon\www\TunggalJayaTransport\public\index.php:20

## Request

GET /booking

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IkFkbTQ4dGxMakIyS3BZeDQvTEozYkE9PSIsInZhbHVlIjoiV1RwY25JSHhUMjBQeEhtUmhoU045YzJETDJpWnZPdm9kTkxyQXJFdTJMY3RFNjZ5MkwzbkttVnBPNHNqMjNZMGwyazBwejgxTTgwaHY1eGlYdEJQZFpBSTg3MDRKbHgwdHAyY3ZrWndFU0t0V0RUd1FhZnFRNG5LYk9CUGVxNjdubjJmVjRJQmgrVVphb0xDUGRHMEYyTkJGNFFhSks3cjRYR1FQOEhScnF6RHV1TkViNkxJRkM1MHBXeXFkalBBWG5GenlLMFNyTVc2NWVBVHFzakVoMC8vRmhKR0Vza2d3WUlNdm1aWjBkWT0iLCJtYWMiOiI3NzY5YTI5NmYzZDQ2MGYzYzI4ZGYxZGVlMDkxNmVhYmQ3ZWM4MmIzZTI3MGE0MDhjMjkwOTk4ZjE2Y2UyMTBlIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6Inp5UVhkclRENXMrMjRvWFpXWlhzRFE9PSIsInZhbHVlIjoiamg5UDFqYmtsVFk1by93M3doRStXbnhjOFlMczRuK1N1SzhWZk55ZVBvY25sQnRjakFucUVzOFk2UkJraEZmVVlyeW1iYld6T01nSVFMWXREdWNTV2QrQ01rRW84VzR0M3VLbDdKdkJMS0ZPZjBtRmFkdTlQMTNmTDJSOG9PZjYiLCJtYWMiOiI5YWM2ZmM5NTk1MmVlYzE3YzU2N2RkYjRkNjUwYmNhM2E3MWFmZTVhOTQxMGY4Zjk0ODc1NzNlYWExZjcxNDU5IiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6InN6djlwN2IzMHZwc3l3akFzQ0lpdmc9PSIsInZhbHVlIjoiM1hkNlVsUWFqOWw2SStGcHdSRzJmNEx6T0hQcVVXdlBQSmFLeUR1SW9yR2F3a0VMNEQweUdmTlJHQzI2UU1CK1haUG96RlFuenpWNENIYzY0dmlJYmxFblVOQVU5cThpMnNjVDdNYnIybGxsK2JMVEVxMjdoam5wR1dNcHRYMUUiLCJtYWMiOiI4M2NjN2M4NWQyMTYzNGJkMmVkNGVlZTJiMzQ5NTJkMzVmMmIwMDI4NjkzNThlYTk0OGJiNzJkMjExZjcxOTQyIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6IndPT1JGMjBoTEMydnU2VEM4NGdWREE9PSIsInZhbHVlIjoiYjhSUUdOUzJ2andYcDcrQ0tMMVRaQy9zUFE0N3N0RklVK1ZxUEhqNmp1L1J6bCtRSnFKUFBvc3FYZlFSQllKd1JFYnRobjZNbFNpQitBNGNZMlg4ekM2cHhJTHNwcHRLR1lObkVBSkJ1bU1xZ3V2eE9lM2kvTEIrZmdEUk9PRHIiLCJtYWMiOiJiMGVjYTk3ZjhlYTNiZTYwODMwYWFiZGUxYTkzMzFkMDk1NWE0ZWJhYzI3ZDhiMWQwMTE1MzE4MmI5YzEwZDViIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@index
route name: frontend.booking.index

## Route Parameters

No route parameter data available.

## Database Queries

No database queries detected.
