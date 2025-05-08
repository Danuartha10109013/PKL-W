<?php

namespace App\Http\Controllers;

use App\Models\KomisiM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
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
            switch ($division) {
                case 'Sales Enginer':
                    $query->whereJsonContains('penerimase', $userId);
                    $data = $query->whereJsonContains('penerimase', $userId)->get();
                    $sum = 0;

                    foreach ($data as $item) {
                        $penerimaCount = count(json_decode($item->penerimase, true));
                        $in = ($item->se ?? 0) / max(1, $penerimaCount);
                        // dd($se);
                        $sum += $in;
                    }

                    break;
                case 'Aplication Service':
                    $query->whereJsonContains('penerimaap', $userId);
                    $data = $query->whereJsonContains('penerimaap', $userId)->get();
                    $sum = 0;

                    foreach ($data as $item) {
                        $penerimaCount = count(json_decode($item->penerimaap, true));
                        $in = ($item->as ?? 0) / max(1, $penerimaCount);
                        // dd($se);
                        $sum += $in;
                    }
                    break;
                case 'Administration':
                    $query->whereJsonContains('penerimaadm', $userId);
                    $data = $query->whereJsonContains('penerimaadm', $userId)->get();
                    $sum = 0;

                    foreach ($data as $item) {
                        $penerimaCount = count(json_decode($item->penerimaadm, true));
                        $in = ($item->adm ?? 0) / max(1, $penerimaCount);
                        // dd($se);
                        $sum += $in;
                    }
                    break;
                case 'Manager':
                    $query->whereJsonContains('penerimamng', $userId);
                    $data = $query->whereJsonContains('penerimamng', $userId)->get();
                    $sum = 0;
                    
                    foreach ($data as $item) {
                        $penerimaCount = count(json_decode($item->penerimamng, true));
                        $in = ($item->mng ?? 0) / max(1, $penerimaCount);
                        // dd($penerimaCount);
                        $sum += $in;
                    }
                    break;
                default:
                    $data = collect(); // Division tidak cocok
            }

$data = $query->get();

// dd($data); // akan berisi Collection

        
            // Calculate the sum
        
            // Pass data and filter parameters to the view
            return view('pages.penerima.index', compact('data', 'sum', 'from', 'to', 'division','penerimaCount'));
        }
}
