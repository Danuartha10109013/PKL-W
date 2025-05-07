<?php

namespace App\Http\Controllers;

use App\Models\CalculationM;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index (){
        // $data = CalculationM::all();
        $data = CalculationM::where('id',1)->get();

        return view('pages.admin.calculation.index',compact('data'));
    }


    public function updateInline(Request $request)
{
    // Validasi input
    $data = $request->validate([
        'id' => 'required|integer',
        'field' => 'required|string',
        'value' => 'nullable|string'
    ]);

    // Cari record berdasarkan ID
    $record = CalculationM::find($data['id']);
    if (!$record) {
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
    }

    // Field yang diizinkan
    $allowedFields = ['it', 'se', 'as', 'adm', 'mng'];
    if (!in_array($data['field'], $allowedFields)) {
        return response()->json(['success' => false, 'message' => 'Field tidak valid.']);
    }

    // Simulasikan update
    $record->{$data['field']} = $data['value'];

    // Field yang dihitung totalnya (kecuali 'it')
    $fieldsToSum = ['se', 'as', 'adm', 'mng'];
    $total = 0;
    foreach ($fieldsToSum as $field) {
        $total += floatval($record->{$field} ?? 0);
    }

    // Validasi jika total melebihi 1
    if ($total > 1) {
        return response()->json([
            'success' => false,
            'message' => 'Total persentase melebihi 100%.'
        ]);
    }

    // Simpan perubahan jika valid
    $record->save();

    return response()->json(['success' => true]);
}

    

}
