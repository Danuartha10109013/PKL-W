<?php

namespace App\Http\Controllers;

use App\Models\KomisiM;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function penrimaIncentive(){
        $data = KomisiM::orderBy('created_at','asc')->get();
        $sum = 0;
        return view('pages.penjualan.incentive',compact('data','sum'));
    }
    public function penrimaIncentiveDirektur(){
        $data = KomisiM::orderBy('created_at','desc')->get();
        $sum = 0;
        return view('pages.admin.laporan.incentive',compact('data','sum'));
    }

    // public function index()
    // {
    //     $dataPerBulan = KomisiM::select(
    //         DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
    //         DB::raw("SUM(total_sp) as total_per_bulan")
    //     )
    //     ->groupBy('bulan')
    //     ->orderBy('bulan', 'asc')
    //     ->get();

    //     // Hitung rata-rata bergerak dimulai dari bulan ke-4 (index ke-3)
    //     $dataPerBulan = $dataPerBulan->map(function ($item, $index) use ($dataPerBulan) {
    //         $item->moving_average = null;

    //         // Mulai dari index ke-3 agar bulan ke-1, 2, dan 3 tetap kosong
    //         if ($index >= 3) {
    //             $sum = $dataPerBulan[$index - 1]->total_per_bulan +
    //                 $dataPerBulan[$index - 2]->total_per_bulan +
    //                 $dataPerBulan[$index - 3]->total_per_bulan;

    //             $item->moving_average = $sum > 0 ? $sum / 3 : 0;
    //         }

    //         return $item;
    //     });

    //     // Ambil data bulan terakhir untuk prediksi bulan berikutnya
    //     $lastMonth = $dataPerBulan->last();
    //     $prediksiBulanDepan = $lastMonth->moving_average ?? 1;
    //     // Pastikan minimal 1 untuk menghindari pembagian nol
    
    //     return view('pages.target.index', compact('dataPerBulan', 'prediksiBulanDepan'));
    // }

    public function index()
{
    $dataPerBulan = KomisiM::select(
        DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
        DB::raw("SUM(total_sp) as total_per_bulan")
    )
    ->groupBy('bulan')
    ->orderBy('bulan', 'asc')
    ->get();

    // Hitung rata-rata bergerak dimulai dari bulan ke-3 (index ke-2)
    $dataPerBulan = $dataPerBulan->map(function ($item, $index) use ($dataPerBulan) {
        $item->moving_average = null;

        // Mulai dari index ke-2 agar bulan ke-1 dan 2 tetap kosong
        if ($index >= 2) {
            $sum = $dataPerBulan[$index - 1]->total_per_bulan +
                   $dataPerBulan[$index - 2]->total_per_bulan;

            $item->moving_average = $sum > 0 ? $sum / 2 : 0;
        }

        return $item;
    });

    // Ambil data bulan terakhir untuk prediksi bulan berikutnya
    $lastMonth = $dataPerBulan->last();
    $prediksiBulanDepan = $lastMonth->moving_average ?? 1;

    return view('pages.target.index', compact('dataPerBulan', 'prediksiBulanDepan'));
}


    public function laporan(){
         $dataPerBulan = KomisiM::select(
        DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
        DB::raw("SUM(total_sp) as total_per_bulan")
    )
    ->groupBy('bulan')
    ->orderBy('bulan', 'asc')
    ->get();

    // Hitung rata-rata bergerak dimulai dari bulan ke-3 (index ke-2)
    $dataPerBulan = $dataPerBulan->map(function ($item, $index) use ($dataPerBulan) {
        $item->moving_average = null;

        // Mulai dari index ke-2 agar bulan ke-1 dan 2 tetap kosong
        if ($index >= 2) {
            $sum = $dataPerBulan[$index - 1]->total_per_bulan +
                   $dataPerBulan[$index - 2]->total_per_bulan;

            $item->moving_average = $sum > 0 ? $sum / 2 : 0;
        }

        return $item;
    });

    // Ambil data bulan terakhir untuk prediksi bulan berikutnya
    $lastMonth = $dataPerBulan->last();
    $prediksiBulanDepan = $lastMonth->moving_average ?? 1;
        // Pastikan minimal 1 untuk menghindari pembagian nol
        return view('pages.admin.laporan.target',compact('dataPerBulan', 'prediksiBulanDepan'));
    }
    
    public function incentive(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');

    $user = Auth::user();
    $userId = (string) $user->id;
    $division = $user->division;

    $query = KomisiM::orderBy('created_at','asc');

    if ($from && $to) {
        $query->whereBetween('created_at', [$from, $to]);
    }

    // Ambil semua data terlebih dahulu (hanya sekali)
    $allData = $query->get();

    $data = collect(); // Data akhir yang cocok dengan user
    $sum = 0;
    $penerimaCount = 0;

    foreach ($allData as $item) {
        // Tentukan field berdasarkan divisi
        switch ($division) {
            case 'Sales Enginer':
                $penerimaField = 'penerimase';
                $amountField = 'se';
                break;
            case 'Aplication Service':
                $penerimaField = 'penerimaap';
                $amountField = 'as';
                break;
            case 'Administration':
                $penerimaField = 'penerimaadm';
                $amountField = 'adm';
                break;
            case 'Manager':
                $penerimaField = 'penerimamng';
                $amountField = 'mng';
                break;
            default:
                $penerimaField = null;
                $amountField = null;
        }

        // Jika tidak ada field yang sesuai, skip loop
        if (!$penerimaField || !$amountField) {
            continue;
        }

        $penerima = json_decode($item->$penerimaField, true);

        if (is_array($penerima) && collect($penerima)->contains('id', $userId)) {
            $penerimaCount = count($penerima);
            $incentive = ($item->$amountField ?? 0) / max(1, $penerimaCount);
            $sum += $incentive;
            $data->push($item); // Simpan hanya data relevan
        }
    }

    return view('pages.penerima.index', compact('data', 'sum', 'from', 'to', 'division', 'penerimaCount'));
}


        public function confirmation($id, $inId)
{
    $komisi = KomisiM::findOrFail($inId);
    $userId = (string) $id; // pastikan dalam bentuk string karena di JSON tersimpan sebagai string

    // Daftar kolom JSON yang mungkin menyimpan data user
    $fields = ['penerimase', 'penerimaap', 'penerimaadm', 'penerimamng'];

    foreach ($fields as $field) {
        $data = json_decode($komisi->$field, true);

        if (is_array($data)) {
            $updated = false;

            foreach ($data as &$item) {
                if (isset($item['id']) && $item['id'] == $userId) {
                    $item['status'] = 1;
                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                $komisi->$field = json_encode($data);
                break; // berhenti setelah satu kolom ditemukan dan diubah
            }
        }
    }

    $komisi->save();

    return redirect()->back()->with('success', 'Status berhasil dikonfirmasi.');
}

public function catatan(Request $request, $id, $inId)
{
    $komisi = KomisiM::findOrFail($inId);
    $user = User::findOrFail($id);
    $division = $user->division;
    $field = null;

    // Tentukan field berdasarkan division
    switch ($division) {
        case 'Sales Enginer':
            $field = 'penerimase';
            break;
        case 'Aplication Service':
            $field = 'penerimaap';
            break;
        case 'Administration':
            $field = 'penerimaadm';
            break;
        case 'Manager':
            $field = 'penerimamng';
            break;
        default:
            return redirect()->back()->with('error', 'Divisi tidak dikenali.');
    }

    // Decode JSON dari field yang sesuai
    $penerima = json_decode($komisi->$field, true);

    // Ubah nilai catatan hanya untuk user yang sesuai
    foreach ($penerima as &$item) {
        if ($item['id'] == $id) {
            $item['catatan'] = $request->catatan;
            break;
        }
    }

    // Encode kembali dan simpan
    $komisi->$field = json_encode($penerima);
    $komisi->save();

    return redirect()->back()->with('success', 'Catatan berhasil dikirim.');
}


}
