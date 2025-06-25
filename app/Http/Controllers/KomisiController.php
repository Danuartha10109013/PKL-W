<?php

namespace App\Http\Controllers;

use App\Models\CalculationM;
use App\Models\KomisiCostumerM;
use App\Models\KomisiM;
use App\Models\KomisiPenjualanM;
use App\Models\User;
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

    public function add(Request $request){
        // dd($request->all());

        $call= CalculationM::where('active',1)->orderBy('created_at','desc')->get();
        
        if ($request->has('no_jobcard')) {
            $no_jobcard = $request->no_jobcard;
            return view('pages.penjualan.add',compact('call','no_jobcard'));
        }else{
            return view('pages.penjualan.add',compact('call'));
        }

    }

    public function store(Request $request)
    {
        // dd($request->calculation);
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
            'sales_enginer' => 'array|max:3',
            'aplication_service' => 'array|max:2',
            'administration' => 'array|max:3',
            'manager' => 'array|max:1',
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
        $cal0= CalculationM::find($request->calculation);
        if(!$cal0){
            return redirect()->back()->with('error', 'Nilai Kalkulasi tidak terdefinisi')
        ;}
        // dd($cal0);
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
        $komisi->penerimase = json_encode(collect($request->sales_enginer)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0'];
        }));
        
        $komisi->penerimaap = json_encode(collect($request->aplication_service)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0'];
        }));
        
        $komisi->penerimaadm = json_encode(collect($request->administration)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0'];
        }));
        
        $komisi->penerimamng = json_encode(collect($request->manager)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0'];
        }));
        
        
        
        // Save the Komisi Penjualan entry
        $komisi->save();

        $cal1= CalculationM::find($request->calculation);
        
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
    
public function dibayar($id, $inId)
{
    $komisi = KomisiM::findOrFail($inId);

    // Tentukan field sesuai division user
    $divisionMap = [
        'Sales Enginer' => 'penerimase',
        'Aplication Service' => 'penerimaas',
        'Administration' => 'penerimaadm',
        'Manager' => 'penerimamng',
    ];

    $user = User::findOrFail($id);
    $divisionField = $divisionMap[$user->division] ?? null;
    // dd($divisionField);
    if ($divisionField && $komisi->$divisionField) {
        $data = json_decode($komisi->$divisionField, true);

        if (is_array($data)) {
            foreach ($data as &$item) {
                if (isset($item['id']) && $item['id'] == $id) {
                    $item['dibayar'] = 1; // pastikan disimpan sebagai integer
                    break;
                }
            }

            $komisi->$divisionField = json_encode($data);
            $komisi->save();
        }
    }

    return redirect()->back()->with('success', 'Status pembayaran telah dikonfirmasi.');
}

}
