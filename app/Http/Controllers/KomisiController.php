<?php

namespace App\Http\Controllers;

use App\Models\CalculationM;
use App\Models\KomisiCostumerM;
use App\Models\KomisiM;
use App\Models\KomisiPenjualanM;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KomisiController extends Controller
{
    public function index(Request $request){
        // dd($request->all());
        if($request->persentase){

            $komisis = KomisiM::where('calculation',$request->persentase)->orderBy('created_at','desc')->get();
            $komisi = KomisiM::latest()->first();
            $persen= CalculationM::find($request->persentase);
            $call= CalculationM::find($request->persentase);
            // dd($komisis);
            $komisi_customer = KomisiCostumerM::orderBy('created_at','desc')->get();
            return view('pages.penjualan.index',compact('komisis','komisi_customer','persen','call'));
        }else{
            
            $komisis = KomisiM::orderBy('created_at','desc')->get();
            $komisi = KomisiM::latest()->first();
            // dd($komisis);
            if($komisi){
                $call= CalculationM::find($komisi->calculation);
            }else{
                $call= CalculationM::latest()->first();
            }
            
            // dd($komisis);
            $komisi_customer = KomisiCostumerM::orderBy('created_at','desc')->get();
            return view('pages.penjualan.index',compact('komisis','komisi_customer','call'));
        }
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
        $komisi->calculation = $request->calculation;
        $komisi->sales_name = $request->sales_name;
        $komisi->no_jo = $no_jo;
        $komisi->jo_date = now()->toDateString();
        $komisi->kurs = $request->kurs;
        $komisi->penerimase = json_encode(collect($request->sales_enginer)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0', 'bukti_kirim' => '', 'bukti_terima' => ''];
        }));
        
        $komisi->penerimaap = json_encode(collect($request->aplication_service)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0', 'bukti_kirim' => '', 'bukti_terima' => ''];
        }));
        
        $komisi->penerimaadm = json_encode(collect($request->administration)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0', 'bukti_kirim' => '', 'bukti_terima' => ''];
        }));
        
        $komisi->penerimamng = json_encode(collect($request->manager)->map(function ($id) {
            return ['id' => $id, 'status' => '0', 'catatan' => '', 'dibayar' => '0', 'bukti_kirim' => '', 'bukti_terima' => ''];
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
        return redirect()->back()->with('success', 'Komisi Telah Berhasil Dihapus');
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

        if ($from && $to) {
            // Tambahkan 1 hari ke tanggal "to" agar seluruh data pada hari itu juga ikut terambil
            $toPlusOne = Carbon::parse($to)->addDay();

            $data = KomisiM::where('created_at', '>=', $from)
                        ->where('created_at', '<', $toPlusOne)
                        ->get();
        } else {
            $data = KomisiM::all();
        }
    
        // Calculate the sum
        $sum = $data->sum('it');
    
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

public function dibayar(Request $request, $id, $inId)
{
    // Validasi input file
    $request->validate([
        'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Ambil data komisi yang sedang diakses
    $komisi = KomisiM::findOrFail($inId);

    // Ambil data komisi sebelumnya berdasarkan created_at (bukan ID)
    $data_sebelumnya = KomisiM::where('created_at', '<', $komisi->created_at)
        ->orderBy('created_at', 'desc')
        ->first();

    // Cek jika sebelumnya ada, dan field penerima terkait belum dikonfirmasi
    if ($data_sebelumnya) {
        $user = User::findOrFail($id);
        $divisionMap = [
            'Sales Enginer' => 'penerimase',
            'Aplication Service' => 'penerimaap',
            'Administration' => 'penerimaadm',
            'Manager' => 'penerimamng',
        ];

        $divisionField = $divisionMap[$user->division] ?? null;

        if ($divisionField && $data_sebelumnya->$divisionField) {
            $prevData = json_decode($data_sebelumnya->$divisionField, true);

            if (is_array($prevData)) {
                foreach ($prevData as $item) {
                    if (isset($item['id']) && $item['id'] == $id) {
                        if (empty($item['bukti_terima'])) {
                            return redirect()->back()->with('error', 'Incentive sebelumnya dari user ini belum dikonfirmasi. yaitu pada : '.$data_sebelumnya->no_it);
                        }
                        break;
                    }
                }
            }
        }
    }

    // Upload bukti dan update status pada data sekarang
    $divisionMap = [
        'Sales Enginer' => 'penerimase',
        'Aplication Service' => 'penerimaap',
        'Administration' => 'penerimaadm',
        'Manager' => 'penerimamng',
    ];

    $user = User::findOrFail($id);
    $divisionField = $divisionMap[$user->division] ?? null;

    if ($divisionField && $komisi->$divisionField) {
        $data = json_decode($komisi->$divisionField, true);

        if (is_array($data)) {
            foreach ($data as &$item) {
                if (isset($item['id']) && $item['id'] == $id) {
                    // Upload file
                    if ($request->hasFile('bukti_pembayaran')) {
                        $file = $request->file('bukti_pembayaran');
                        $originalName = time() . '_' . $file->getClientOriginalName(); // hindari nama ganda
                        $path = $file->storeAs('public/bukti_pembayaran', $originalName);
                        $publicPath = 'storage/bukti_pembayaran/' . $originalName;

                        $item['dibayar'] = 1;
                        $item['bukti_kirim'] = $publicPath;
                    }
                    break;
                }
            }

            // Simpan perubahan ke model
            $komisi->$divisionField = json_encode($data);
            $komisi->save();
        }
    }

    return redirect()->back()->with('success', 'Status pembayaran telah dikonfirmasi.');
}

public function pendapatan(){
    $data = KomisiM::all();
    return view('pages.admin.laporan.pendapatan',compact('data'));
}

// public function dibayar($id, $inId)
// {
//     // dd($inId);
//     $komisi = KomisiM::findOrFail($inId);

//     // Tentukan field sesuai division user
//     $divisionMap = [
//         'Sales Enginer' => 'penerimase',
//         'Aplication Service' => 'penerimaap',
//         'Administration' => 'penerimaadm',
//         'Manager' => 'penerimamng',
//     ];

//     $user = User::findOrFail($id);
//     $divisionField = $divisionMap[$user->division] ?? null;
//     // dd($divisionField && $komisi->$divisionField);
//     if ($divisionField && $komisi->$divisionField) {
//         $data = json_decode($komisi->$divisionField, true);
//         // dd($data);

//         if (is_array($data)) {
//             foreach ($data as &$item) {
//                 if (isset($item['id']) && $item['id'] == $id) {
//                     $item['dibayar'] = 1; // pastikan disimpan sebagai integer
//                     break;
//                 }
//             }

//             $komisi->$divisionField = json_encode($data);
//             $komisi->save();
//         }
//     }

//     return redirect()->back()->with('success', 'Status pembayaran telah dikonfirmasi.');
// }

}
