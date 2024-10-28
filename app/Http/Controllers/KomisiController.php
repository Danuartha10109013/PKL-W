<?php

namespace App\Http\Controllers;

use App\Models\KomisiM;
use App\Models\KomisiPenjualanM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomisiController extends Controller
{
    public function index(){
        $komisis = KomisiM::all();
        return view('pages.penjualan.index',compact('komisis'));
    }

    public function add(){
        return view('pages.penjualan.add');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request data directly
        $request->validate([
            'no_jobcard' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'date' => 'required|date',
            'no_po' => 'required|string|max:255',
            'kurs' => 'required|numeric',
            'totalbop' => 'required|numeric',
            'totalsp' => 'required|numeric',
            'totalbp' => 'required|numeric',
            'po_date' => 'required|date',
            'po_received' => 'required|date',
            'no_form' => 'required|string|max:255',
            'effective_date' => 'required|date',
            'no_revisi' => 'required|string|max:255',
        ]);

        $komisi = new KomisiM();
        $komisi->no = $request->no;
        $komisi->no_jobcard = $request->no_jobcard;
        $komisi->customer_name = $request->customer_name;
        $komisi->date = $request->date;
        $komisi->no_po = $request->no_po;
        $komisi->kurs = $request->kurs;
        $komisi->gp = $request->totalsp - $request->totalbop; 
        $komisi->it = $komisi->gp * 0.20;
        $komisi->se = $komisi->it * 0.70;
        $komisi->as = $komisi->it * 0.10;
        $komisi->adm = $komisi->it * 0.10;
        $komisi->mng = $komisi->it * 0.10;
        $komisi->no_jo = "";
        $komisi->jo_date = "";
        $komisi->kurs = $request->kurs;
        

        // Save the Komisi Penjualan entry
        $komisi->save();

        // Return a success message
        return redirect()->route('pegawai.komisi')->with('success', 'Komisi Penjualan saved successfully!');
    
    }
    
    
}
