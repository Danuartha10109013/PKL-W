<?php

namespace App\Http\Controllers;

use App\Models\KomisiM;
use Illuminate\Http\Request;
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
    
    
    
}
