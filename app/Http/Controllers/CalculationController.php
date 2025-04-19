<?php

namespace App\Http\Controllers;

use App\Models\CalculationM;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index (){
        $data = CalculationM::all();

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

    // Tentukan kolom yang bisa diedit
    $allowedFields = ['it', 'se', 'as', 'adm', 'mng'];

    // Cek apakah field yang diminta valid
    if (!in_array($data['field'], $allowedFields)) {
        return response()->json(['success' => false, 'message' => 'Field tidak valid.']);
    }

    // Update data field
    $record->{$data['field']} = $data['value'];
    $record->save();

    // Return sukses
    return response()->json(['success' => true]);
}

}
