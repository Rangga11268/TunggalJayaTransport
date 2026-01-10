<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $promoCode = PromoCode::where('code', strtoupper($request->code))->first();

        // Check if code exists and is valid (active, date range, usage limit)
        if (!$promoCode || !$promoCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.'
            ], 400);
        }

        // Check minimum purchase amount
        if ($request->total_amount < $promoCode->min_purchase_amount) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal pembelian untuk kode ini adalah Rp ' . number_format($promoCode->min_purchase_amount, 0, ',', '.')
            ], 400);
        }

        $discount = $promoCode->calculateDiscount($request->total_amount);

        return response()->json([
            'valid' => true,
            'promo_code_id' => $promoCode->id,
            'discount_amount' => $discount,
            'final_price' => $request->total_amount - $discount,
            'message' => 'Kode promo berhasil digunakan!'
        ]);
    }
}
