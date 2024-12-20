<?php

namespace App\Http\Controllers;

use App\Models\KomisiM;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function pegawai()
    {
        $komisi = KomisiM::all();

        // Group data by month and sum up `total_sp`
        $sellingPriceData = $komisi->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('F Y'); // Group by month and year
        })->map(function ($group) {
            return $group->sum('total_sp'); // Sum the `total_sp` column
        });
        return view('pages.pegawai.index',compact('sellingPriceData'));
    }
    public function admin()
    {
        $komisi = KomisiM::all();

        // Group data by month and sum up `total_sp`
        $sellingPriceData = $komisi->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('F Y'); // Group by month and year
        })->map(function ($group) {
            return $group->sum('total_sp'); // Sum the `total_sp` column
        });
        return view('pages.admin.index',compact('sellingPriceData'));
    }
}
