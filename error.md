# Error - Internal Server Error
Undefined constant "Milon\Barcode\DNS1D"

PHP 8.3.23
Laravel 12.28.1
tunggaljayatransport.test

## Stack Trace

0 - D:\laragon\www\TunggalJayaTransport\resources\views\frontend\booking\ticket-pdf.blade.php:344
1 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:123
2 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Filesystem\Filesystem.php:124
3 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\PhpEngine.php:57
4 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\Engines\CompilerEngine.php:76
5 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:208
6 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:191
7 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\View\View.php:160
8 - D:\laragon\www\TunggalJayaTransport\vendor\barryvdh\laravel-dompdf\src\PDF.php:142
9 - D:\laragon\www\TunggalJayaTransport\vendor\barryvdh\laravel-dompdf\src\Facade\Pdf.php:66
10 - D:\laragon\www\TunggalJayaTransport\app\Services\TicketPdfService.php:41
11 - D:\laragon\www\TunggalJayaTransport\app\Http\Controllers\Frontend\BookingController.php:534
12 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
13 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
14 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
15 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
16 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
17 - D:\laragon\www\TunggalJayaTransport\vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
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
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IlVadnhibmFTVHI0dWZqb0FqVWFnSEE9PSIsInZhbHVlIjoiSG96RGJkeGE3SFZyUE5MWnIwWkt4bU9GOEI0ckduZHdhWVFHeTJ0b0lXZjA5R0EvcEVsSDNEempzalZ5VkUvbVdGK2lWMldHRmd0QjZEbFozTFBuV3Zvekd3eVRtS0ZyVjdYWDlxM1JRWHZoejJseVhuNUNvSk1JUzljbEhGYTNSY0lvSWhRREpQM3JQdXAyZVNDYWJjd09scm1jSkdRa0RYODN5aDRsWUFuV095VG9YNTJtYmd2WjMzR0hGMGNqR1lOcThIR2dCV2RjNTZ2dEo0WTNDZkFKU25VSmgvTzNVRDBhODZvNHFwaz0iLCJtYWMiOiIxYTc2ZDFiMTIzMmM5NWY2ZDk0OTM4MjY4MGM5ZjZmOTdjZWVjNzcyMDFiZjM5YzgyNGY4NWNjNDY1Nzg4NmZiIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IlBmQ0gyZ1dpandJcmg1cEJnUUJkbFE9PSIsInZhbHVlIjoiNGdTUW5LYzNrejRrNUVaaWdmYU1WREdwY3o0eHdBbUlhS3pwQ1hFM2lOU0IvNEFHOVBiWFhBZzltTXR4NitDWGhvcVJEdnM2RjU0djhjSzNWTVgwY1lxN1VjWmZWQzVwRnU3Zk0rVForMkRuY205TjRhNzRqNUtPeFJyQlR2QTEiLCJtYWMiOiJkODg5NGI5ODMyNTJjNzFhMTI3OTRlNDc3OGYzODYxMTNiZWIyZjFjZDcyOTdiNDdmMDRkZjE3OTQ3NzJkNGU0IiwidGFnIjoiIn0%3D; tunggal-jaya-transport-session=eyJpdiI6Ilp1TUp4VTQveTN0MURweWtxRnpURnc9PSIsInZhbHVlIjoiZ3ZETTVjdWF3aE56dVVSd3JWNStNdTFZc29kL0ZiS3VaNDZZWUlaT1kzNWxydE85UXFNWTZsUUFvb3FEWmNZcDZhTzRuR0pDWjMrZFJ4cUlxcDRFaFFsZmx6YU5jdDc0RWNaWDROWU9sZWhLdHVROEF1dlJwRVluZmpVR1RDOEciLCJtYWMiOiI4OGQ1ZWY2ZmIxYzk4ZGVmMGI5OGNiNDBmYTZjNzBkNDY1Zjk0ODI1NmFiMjJjM2EzOTY3OWVmMzdiZjNhMWJmIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Frontend\BookingController@downloadTicket
route name: frontend.booking.download-ticket
middleware: web

## Route Parameters

{
    "booking": "8"
}

## Database Queries

* mysql - select * from `sessions` where `id` = 'k3Sa8qByqMawREnhMMyx2A0RqPZxv507BJpP9bNs' limit 1 (5.65 ms)
* mysql - select * from `bookings` where `bookings`.`id` = '8' limit 1 (1.32 ms)
* mysql - select * from `schedules` where `schedules`.`id` in (7) (1.19 ms)
* mysql - select * from `routes` where `routes`.`id` in (3) (1.48 ms)
* mysql - select * from `buses` where `buses`.`id` in (1) (1.25 ms)
* mysql - select * from `users` where `id` = 2 limit 1 (1.43 ms)
* mysql - select * from `ticket_customizations` where `ticket_customizations`.`id` = 1 limit 1 (2.16 ms)
