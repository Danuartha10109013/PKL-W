<?php

namespace App\Http\Controllers;

use App\Models\KomisiCostumerM;
use App\Models\KomisiM;
use App\Models\KomisiPenjualanM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomisiController extends Controller
{
    public function index(){
        $komisis = KomisiM::all();
        $komisi_customer = KomisiCostumerM::all();

        return view('pages.penjualan.index',compact('komisis','komisi_customer'));
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

        $lastno = KomisiM::whereDate('created_at', now()->toDateString())->max('no');

        if (!$lastno) {
            $lastno = '001';
        } else {
            // Increment the last number and pad it to three digits
            $lastno = str_pad((int)$lastno + 1, 3, '0', STR_PAD_LEFT);
        }

        // Format today's date in the desired format and concatenate with the last number
        $kode = 'IS.'.now()->format('dmy') .'-'. $lastno;

        $lastjo = KomisiM::whereDate('created_at', now()->toDateString())->max('no_jo');

        if (!$lastjo) {
            $lastjo = '001';
        } else {
            // Increment the last number and pad it to three digits
            $lastjo = str_pad((int)$lastjo + 1, 3, '0', STR_PAD_LEFT);
        }

        // Format today's date in the desired format and concatenate with the last number
        $no_jo = $lastjo.'JO'.'-'.now()->format('dmy') ;
        // dd($kode);

        $komisi = new KomisiM();
        $komisi->no = $kode;
        $komisi->no_jobcard = $request->no_jobcard;
        $komisi->customer_name = $request->customer_name;
        $komisi->date = $request->date;
        $komisi->no_po = $request->no_po;
        $komisi->kurs = $request->kurs;
        $komisi->bop = $request->totalbop;
        $komisi->gp = $request->totalsp - $request->totalbop; 
        $komisi->it = $komisi->gp * 0.20;
        $komisi->se = $komisi->it * 0.70;
        $komisi->as = $komisi->it * 0.10;
        $komisi->adm = $komisi->it * 0.10;
        $komisi->mng = $komisi->it * 0.10;
        $komisi->no_jo = $no_jo;
        $komisi->jo_date = now()->toDateString();
        $komisi->kurs = $request->kurs;
        
        // Save the Komisi Penjualan entry
        $komisi->save();

        //komisi customer
        $komisi_customer = new KomisiCostumerM();
        $komisi_customer->no = $kode;
        $komisi_customer->no_jobcard = $request->no_jobcard;
        $komisi_customer->customer_name = $request->customer_name;
        $komisi_customer->date = $request->date;
        $komisi_customer->no_po = $request->no_po;
        $komisi_customer->kurs = $request->kurs;
        $komisi_customer->bop = $request->totalbop;
        $komisi_customer->gp = $request->totalsp - $request->totalbop; 
        $komisi_customer->it = $komisi_customer->gp * 0.30;
        $komisi_customer->se = $komisi_customer->it * 0.70;
        $komisi_customer->as = $komisi_customer->it * 0.10;
        $komisi_customer->adm = $komisi_customer->it * 0.10;
        $komisi_customer->mng = $komisi_customer->it * 0.10;
        $komisi_customer->no_jo = $no_jo;
        $komisi_customer->jo_date = now()->toDateString();
        $komisi_customer->kurs = $request->kurs;
        
        // Save the Komisi_customer Penjualan entry
        $komisi_customer->save();

        // Return a success message
        return redirect()->route('pegawai.komisi')->with('success', 'Komisi Penjualan saved successfully!');
    
    }

    public function delete($id){
        $data = KomisiM::find($id);
        $data->delete();
        return redirect()->back()->with('success', 'Komis Telah Berhasil Dihapus');
    }
    
    public function print($id){
        $data = KomisiM::find($id);
        return view('pages.penjualan.print',compact('data'));
    }
    public function print_c($id){
        $data = KomisiCostumerM::find($id);
        return view('pages.penjualan.print',compact('data'));
    }

    public function laporan(Request $request)
    {
        // Retrieve filter values
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Filter data based on the provided dates
        if ($from && $to) {
            $data = KomisiM::whereBetween('created_at', [$from, $to])->get();
        } else {
            $data = KomisiM::all(); // Default to all records if no filter applied
        }
    
        // Calculate the sum
        $sum = $data->sum('se');
    
        // Pass data and filter parameters to the view
        return view('pages.admin.laporan.komisi', compact('data', 'sum', 'from', 'to'));
    }
    
    public function print_laporan(Request $request)
    {
        // Retrieve filter values
        $from = $request->input('from');
        $to = $request->input('to');
    
        // Filter data based on the provided dates
        if ($from && $to) {
            $data = KomisiM::whereBetween('created_at', [$from, $to])->get();
        } else {
            $data = KomisiM::all(); // Default to all records if no filter applied
        }
    
        // Calculate the sum
        $sum = $data->sum('se');
    
        // Pass data and filter parameters to the view
        return view('pages.admin.laporan.print', compact('data', 'sum', 'from', 'to'));
    }
    
   
}
