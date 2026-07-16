<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;

trait BulkDeleteTrait
{
    public function bulkDestroy(Request $request, $modelClass)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih.'], 400);
        }

        $modelClass::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' data berhasil dihapus.',
        ]);
    }
}
