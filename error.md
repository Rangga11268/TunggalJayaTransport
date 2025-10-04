# ErrorException - Internal Server Error
Array to string conversion

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\vendor\milon\barcode\src\Milon\Barcode\DNS2D.php:106
1 - D:\laragon\www\TunggalJayaTransport\resources\views\frontend\booking\ticket-pdf.blade.php:358
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:123
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:124
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php:57
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php:76
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:208
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:191
8 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:160
9 - D:\laragon\www\TunggalJayaTransport\vendor\barryvdh\laravel-dompdf\src\PDF.php:142
10 - D:\laragon\www\TunggalJayaTransport\vendor\barryvdh\laravel-dompdf\src\Facade\Pdf.php:66
11 - D:\laragon\www\TunggalJayaTransport\app\Services\TicketPdfService.php:41
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

GET /booking/ticket/8

## Headers

* **host**: tunggaljayatransport.test
* **connection**: keep-alive
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **referer**: http://tunggaljayatransport.test/booking/success/8
* **accept-encoding**: gzip, deflate
* **accept-language**: en-US,en;q=0.9,id;q=0.8
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IlVadnhibmFTVHI0dWZqb0FqVWFnSEE9PSIsInZhbHVlIjoiSG96RGJkeGE3SFZyUE5MWnIwWkt4bU9GOEI0ckduZHdhWVFHeTJ0b0lXZjA5R0EvcEVsSDNEempzalZ5VkUvbVdGK2lWMldHRmd0QjZEbFozTFBuV3Zvekd3eVRtS0ZyVjdYWDlxM1JRWHZoejJseVhuNUNvSk1JUzljbEhGYTNSY0lvSWhRREpQM3JQdXAyZVNDYWJjd09scm1jSkdRa0RYODN5aDRsWUFuV095VG9YNTJtYmd2WjMzR0hGMGNqR1lOcThIR2dCV2RjNTZ2dEo0WTNDZkFKU25VSmgvTzNVRDBhODZvNHFwaz0iLCJtYWMiOiIxYTc2ZDFiMTIzMmM5NWY2ZDk0OTM4MjY4MGM5ZjZmOTdjZWVjNzcyMDFiZjM5YzgyNGY4NWNjNDY1Nzg4NmZiIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IkNBNHdXYlh1SGVoS0xTMlFVTk5kMXc9PSIsInZhbHVlIjoieU9DZklMK1REaFVqRkJIUXMwOVMxZ01qVEtCazA0UTZ3R3lzRE5NMXk2R1NqaE4vR3hzSGRvKzAyRUdGU0g5OVBNMXpPcjlMZk11YnhjS0l6ZGVSU2dnUDA3clJPR00vWjlTYmtHNDNKT2pIUE85MXZLbVJYRUUyVnlFZkh2aUciLCJtYWMiOiIyMDYwYTBmZGZiMWZkYjA3NTVlM2Y0YjgxYzViNGFkNGEzZTYyOGM3MjA4ZDE2MzM3MTk4N2NmMTc0ZjE5NGNiIiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6IlpOWnVBNURBQXZIYU9VTUptQk5qT3c9PSIsInZhbHVlIjoiTnRMbm9GdmFqdDZ6RjAxdEpYd2hqWGF5Y1kwS2xyQWh6ZUpCN1BkTGhxUlRFeVFHWWNkaXJuOHMvSzFRd1UvRE80c04vejNIeWJ5VnJvWW96eThqenJtV2xERzMvV05nQnJ0clZpR1lwb0hpcHhqcWp4bHBHUmhJNjlNcllLSVEiLCJtYWMiOiIxNjEyZmQzNDVhZTkzOTI3N2IyMzMzZTM4MjRlYjk4NzhmYTNkMTQ5ZmQ5YThiMWFjZTU5ZDVmZjQ5MTRjM2EwIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@downloadTicket
route name: frontend.booking.download-ticket
middleware: web

## Route Parameters

{
    "booking": "8"
}

## Database Queries

* mysql - select * from `sessions` where `id` = 'k3Sa8qByqMawREnhMMyx2A0RqPZxv507BJpP9bNs' limit 1 (5.56 ms)
* mysql - select * from `bookings` where `bookings`.`id` = '8' limit 1 (1.15 ms)
* mysql - select * from `schedules` where `schedules`.`id` in (7) (1.31 ms)
* mysql - select * from `routes` where `routes`.`id` in (3) (1.22 ms)
* mysql - select * from `buses` where `buses`.`id` in (1) (1.02 ms)
* mysql - select * from `users` where `id` = 2 limit 1 (1.38 ms)
* mysql - select * from `ticket_customizations` where `ticket_customizations`.`id` = 1 limit 1 (1.26 ms)
