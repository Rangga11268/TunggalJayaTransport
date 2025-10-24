# ErrorException - Internal Server Error
Array to string conversion

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\milon\barcode\src\Milon\Barcode\DNS1D.php:103
1 - D:\laragon\www\TunggalJayaTransport\resources\views\frontend\booking\ticket-pdf.blade.php:452
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:123
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:124
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php:57
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php:76
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:208
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:191
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:160
9 - D:\laragon\www\TunggalJayaTransport\vendor\barryvdh\laravel-dompdf\src\PDF.php:142
10 - D:\laragon\www\TunggalJayaTransport\vendor\barryvdh\laravel-dompdf\src\Facade\Pdf.php:66
11 - D:\laragon\www\TunggalJayaTransport\app\Services\TicketPdfService.php:35
12 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:534
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

GET /booking/ticket/24

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/booking/success/24
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IlF6eDEwdUdkTXdNMlZhd2JEYmxpZmc9PSIsInZhbHVlIjoiUkhIdkpJelZwYjFuWDFYdDlZQTJpUTBHQU0wa0dldDBHVFBCS2RzV1k2K01CWkJoOEVvTElDOEdmOVFVOWlwWnVXQ0pQK3hMalZFaHZabFl5TnJReVJ2NVlvUmF2WnlNaVhxb0ZPSWFUeHRZRi9ndzVJS20rLzhKZHZpNUV5bC9ub0VqeE9iZUlSK2hya1dic3JHNVFZaytmUGx6TkR2cHR3Q1pVR1E0NEVnUi9KTXRUcFNMVzJtMVVuM055ZkN6R3haL2ZoK2tXNzBncDkyQTBnelYxM29xMUZEOUU0NlZaMnI3eDR1MFl5cz0iLCJtYWMiOiIwNWMzYmE5ZDA2NDI1ZGI4ZGJmZWFmZTI0NGE5OTI1M2RlNGRmNTU0NTU4YjI4YTNkZDM2ODA0OGYwNzEyOTcxIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IkRBTmFPa0pJSUV4RFJzVXRMeFpFR2c9PSIsInZhbHVlIjoiTEt1cTloQ1BNcHZmOG9Ba0xlQ0IrRFJldmUrZVgwdmRkSEF4Z0l6UnI0K0lYSlBwV3Ywa28xaUs5Z3Rhb3hhVkErT042QURSem82aklsb1NJU2hQWkQveDNSYUtDMlc5OEVxY1F1eFNtQ2JYSXdsUEpubnNmY3lJeDlDa1grRVgiLCJtYWMiOiJjZTQzOWYzNDhhNjI2NTIwNTgzODc0YWU0ZWJkY2UxMWI3NDY3ODJjMWZkZTc4MWFiOWU3ZDAxMzczYjFlNzU3IiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6IktnVk1rZE0vWFFLWmZaSnJGVitpcUE9PSIsInZhbHVlIjoiNURucUd1b3p5V1hMYlFNY1ZCSHdNM0t3YWV5aGlwaXJlSGdYYlhjMXBlRmU3Y2xIaFlLQmh2djRTZmxXcXgvOUFIZWNBTVRHYUdsUFBsV20rU2x2Uy9Dcllqd005dGlkWTVZUnNxQUw3VDdpUlFueWZWYk1RQUJlR2NYWjRhSEoiLCJtYWMiOiJkN2E1Yzg0ZmVlZmQ1NTNiZDQyZmFkZGFkYTVhZWEyZDY3NDExODc3ZWVlMjdhOTQ4NWRjYTVmODI4ZWY0OTM1IiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@downloadTicket
route name: frontend.booking.download-ticket
middleware: web

## Route Parameters

{
    "booking": "24"
}

## Database Queries

* mysql - select * from `sessions` where `id` = 'dmr5uuk4X1b4MjJCxZCZiZhSZztqUB3tEXJAg1g8' limit 1 (6.26 ms)
* mysql - select * from `bookings` where `bookings`.`id` = '24' limit 1 (1.46 ms)
* mysql - select * from `schedules` where `schedules`.`id` in (7) (1.52 ms)
* mysql - select * from `routes` where `routes`.`id` in (3) (1.21 ms)
* mysql - select * from `buses` where `buses`.`id` in (1) (1.39 ms)
* mysql - select * from `users` where `id` = 2 limit 1 (1.37 ms)
