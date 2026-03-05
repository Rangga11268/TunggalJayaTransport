<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect; // Add this import

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $promoCodes = PromoCode::query()
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'promoCodes' => $promoCodes
            ]);
        }

        return Inertia::render('Admin/PromoCodes/Index', [
            'promoCodes' => $promoCodes,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PromoCodes/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code|uppercase',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_purchase_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        PromoCode::create($request->all());

        return Redirect::route('admin.promo-codes.index')->with('success', 'Kode promo berhasil dibuat.');
    }

    public function edit(PromoCode $promoCode)
    {
        return Inertia::render('Admin/PromoCodes/Edit', [
            'promoCode' => $promoCode,
        ]);
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'code' => 'required|string|uppercase|unique:promo_codes,code,' . $promoCode->id,
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_purchase_amount' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $promoCode->update($request->all());

        return Redirect::route('admin.promo-codes.index')->with('success', 'Kode promo berhasil diperbarui.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return Redirect::route('admin.promo-codes.index')->with('success', 'Kode promo berhasil dihapus.');
    }
}
