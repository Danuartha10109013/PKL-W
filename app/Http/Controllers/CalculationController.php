<?php

namespace App\Http\Controllers;

use App\Models\CalculationM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculationController extends Controller
{
    public function index (){
        // $data = CalculationM::all();
        $data = CalculationM::orderBy('created_at','desc')->get();

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

    public function setActive(Request $request)
{
    $id = $request->input('id');
    $active = $request->input('active');

    if ($active == 1) {
        // Reset semua ke 0 dulu
        DB::table('calculation')->update(['active' => 0]);

        // Set yang dipilih ke 1
        DB::table('calculation')->where('id', $id)->update(['active' => 1]);
    } else {
        DB::table('calculation')->where('id', $id)->update(['active' => 0]);
    }

    return response()->json(['success' => true]);
}

public function store(Request $request)
{
    try {
        CalculationM::create([
            'it' => $request->it,
            'se' => $request->se,
            'as' => $request->as,
            'adm' => $request->adm,
            'mng' => $request->mng,
            'active' => 0
        ]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

public function destroy($id)
{
    $item = CalculationM::findOrFail($id);
    $item->delete();

    return redirect()->back()->with('success', 'Data berhasil dihapus.');
}


}
