<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Tunggal Jaya Transport REST API Documentation",
 *      description="Dokumentasi OpenAPI / Swagger Resmi Layanan Pemesanan Tiket AKAP & Sewa Pariwisata Tunggal Jaya Transport.",
 *      @OA\Contact(
 *          email="info@tunggaljayatransport.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8000/api",
 *      description="Local Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Masukkan token Sanctum di sini (Format: Bearer {token})"
 * )
 */
abstract class Controller
{
    //
}
