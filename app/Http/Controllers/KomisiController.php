<?php

namespace App\Http\Controllers;

use App\Models\CalculationM;
use App\Models\KomisiCostumerM;
use App\Models\KomisiM;
use App\Models\KomisiPenjualanM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomisiController extends Controller
{
    public function index(){
        $komisis = KomisiM::orderBy('created_at','desc')->get();
        $komisi_customer = KomisiCostumerM::orderBy('created_at','desc')->get();
        $call= CalculationM::find(1);
        return view('pages.penjualan.index',compact('komisis','komisi_customer','call'));
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
        $cal0= CalculationM::find(1);

        $komisi = new KomisiM();
        $komisi->no = $kode;
        $komisi->no_jobcard = $request->no_jobcard;
        $komisi->customer_name = $request->customer_name;
        $komisi->date = $request->date;
        $komisi->no_po = $request->no_po;
        $komisi->kurs = $request->kurs;
        $komisi->bop = $request->totalbop;
        $komisi->gp = $request->totalsp - $request->totalbop; 
        $komisi->total_sp = $request->totalsp; 
        $komisi->it = $komisi->gp * $cal0->it;
        $komisi->se = $komisi->it * $cal0->se;
        $komisi->as = $komisi->it * $cal0->as;
        $komisi->adm = $komisi->it * $cal0->adm;
        $komisi->mng = $komisi->it * $cal0->mng;
        $komisi->no_it = $request->no_it;
        $komisi->sales_name = $request->sales_name;
        $komisi->no_jo = $no_jo;
        $komisi->jo_date = now()->toDateString();
        $komisi->kurs = $request->kurs;
        
        // Save the Komisi Penjualan entry
        $komisi->save();

        $cal1= CalculationM::find(2);
        
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
        $komisi_customer->total_sp = $request->totalsp; 
        $komisi_customer->it = $komisi_customer->gp * $cal1->it;
        $komisi_customer->se = $komisi_customer->it * $cal1->se;
        $komisi_customer->as = $komisi_customer->it * $cal1->as;
        $komisi_customer->adm = $komisi_customer->it * $cal1->adm;
        $komisi_customer->mng = $komisi_customer->it * $cal1->mng;
        $komisi_customer->no_jo = $no_jo;
        $komisi_customer->no_it = $request->no_it;
        $komisi_customer->sales_name = $request->sales_name;
        $komisi_customer->jo_date = now()->toDateString();
        $komisi_customer->kurs = $request->kurs;
        
        // Save the Komisi_customer Penjualan entry
        $komisi_customer->save();

        // Return a success message
        return redirect()->route('pegawai.komisi')->with('success', 'Komisi Penjualan saved successfully!');
    
    }

    public function update(Request $request,$id){
        // dd($id);
        $data = KomisiM::find($id);
        $data->no_it = $request->no_it;
        $data->sales_name = $request->sales_name;
        $data->save();
        $data1 = KomisiCostumerM::find($id);
        $data1->no_it = $request->no_it;
        $data1->sales_name = $request->sales_name;
        $data1->save();

        return redirect()->back()->with('success', 'Incentive sales has been Udated');
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
