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
        $data = KomisiM::all();
        $sum = 0;
        return view('pages.penjualan.incentive',compact('data','sum'));
    }
    public function penrimaIncentiveDirektur(){
        $data = KomisiM::all();
        $sum = 0;
        return view('pages.admin.laporan.incentive',compact('data','sum'));
    }

    public function index()
    {
        $dataPerBulan = KomisiM::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
            DB::raw("SUM(total_sp) as total_per_bulan")
        )
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();
    
        // Hitung rata-rata bergerak (3 bulan terakhir)
        $dataPerBulan = $dataPerBulan->map(function ($item, $index) use ($dataPerBulan) {
            $item->moving_average = null;
    
            if ($index >= 2) {
                $sum = $dataPerBulan[$index - 1]->total_per_bulan +
                       $dataPerBulan[$index - 2]->total_per_bulan +
                       $item->total_per_bulan;
    
                $item->moving_average = $sum > 0 ? $sum / 3 : 0;
            }
    
            return $item;
        });
    
        // Ambil data bulan terakhir untuk target
        $lastMonth = $dataPerBulan->last();
        $prediksiBulanDepan = $lastMonth->moving_average ?? 1; // Pastikan minimal 1 untuk menghindari pembagian nol
    
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
    
        // Hitung rata-rata bergerak (3 bulan terakhir)
        $dataPerBulan = $dataPerBulan->map(function ($item, $index) use ($dataPerBulan) {
            $item->moving_average = null;
    
            if ($index >= 2) {
                $sum = $dataPerBulan[$index - 1]->total_per_bulan +
                       $dataPerBulan[$index - 2]->total_per_bulan +
                       $item->total_per_bulan;
    
                $item->moving_average = $sum > 0 ? $sum / 3 : 0;
            }
    
            return $item;
        });
    
        // Ambil data bulan terakhir untuk target
        $lastMonth = $dataPerBulan->last();
        $prediksiBulanDepan = $lastMonth->moving_average ?? 1;
        return view('pages.admin.laporan.target',compact('dataPerBulan', 'prediksiBulanDepan'));
    }
    
    public function incentive(Request $request){
        
            // Retrieve filter values
            $from = $request->input('from');
            $to = $request->input('to');

            $user = Auth::user();
            $userId = (string) Auth::id();
            $division = $user->division;

            // Mulai query dasar
            $query = KomisiM::query();

            // Tambahkan filter tanggal jika ada
            if ($from && $to) {
                $query->whereBetween('created_at', [$from, $to]);
            }

            // Filter berdasarkan division dan userId
            $sum = 0;

            switch ($division) {
                case 'Sales Enginer':
                    $data = $query->get(); // Ambil semua data dulu
                    foreach ($data as $item) {
                        $penerima = json_decode($item->penerimase, true); // array of ['id' => ..., 'status' => ...]
                        if (collect($penerima)->contains('id', (string) $userId)) {
                            $penerimaCount = count($penerima);
                            $in = ($item->se ?? 0) / max(1, $penerimaCount);
                            $sum += $in;
                        }
                    }
                    break;

                case 'Aplication Service':
                    $data = $query->get();
                    foreach ($data as $item) {
                        $penerima = json_decode($item->penerimaap, true);
                        if (collect($penerima)->contains('id', (string) $userId)) {
                            $penerimaCount = count($penerima);
                            $in = ($item->as ?? 0) / max(1, $penerimaCount);
                            $sum += $in;
                        }
                    }
                    break;

                case 'Administration':
                    $data = $query->get();
                    foreach ($data as $item) {
                        $penerima = json_decode($item->penerimaadm, true);
                        if (collect($penerima)->contains('id', (string) $userId)) {
                            $penerimaCount = count($penerima);
                            $in = ($item->adm ?? 0) / max(1, $penerimaCount);
                            $sum += $in;
                        }
                    }
                    break;

                case 'Manager':
                    $data = $query->get();
                    foreach ($data as $item) {
                        $penerima = json_decode($item->penerimamng, true);
                        if (collect($penerima)->contains('id', (string) $userId)) {
                            $penerimaCount = count($penerima);
                            $in = ($item->mng ?? 0) / max(1, $penerimaCount);
                            $sum += $in;
                        }
                    }
                    break;

                default:
                    $data = collect(); // Kosongkan jika division tidak cocok
            }


            $data = $query->get();

// dd($data); // akan berisi Collection

        
            // Calculate the sum
        
            // Pass data and filter parameters to the view
            return view('pages.penerima.index', compact('data', 'sum', 'from', 'to', 'division','penerimaCount'));
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
