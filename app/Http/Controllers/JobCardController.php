<?php
// app/Http/Controllers/JobCardController.php
namespace App\Http\Controllers;

use App\Models\JobCardM;
use App\Models\KomisiM;
use App\Models\KomisiPenjualanM;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobCardController extends Controller
{

    public function cc(){
    $client = new Client();
    $url = 'http://127.0.0.1:8001/api/data-api';
    $response = $client->request('GET',$url);
    // dd($response);
    $data =  json_decode($response->getBody()->getContents());
    // dd($data);
    }

public function searchJobCard(Request $request)
{
    $client = new Client();
    $url = 'http://127.0.0.1:8001/api/data-api';

    // Fetch data from the API
    $response = $client->request('GET', $url);
    $data = json_decode($response->getBody()->getContents());

    // Get the search query
    $query = $request->input('query');

    // Ambil semua no_jobcard dari KomisiM
    $existingJobCards = KomisiM::pluck('no_jobcard')->toArray();

    // Filter the data for matching `no_jobcard` dan yang belum ada di KomisiM
    $jobCards = collect($data)->filter(function ($item) use ($query, $existingJobCards) {
        return isset($item->no_jobcard)
            && stripos($item->no_jobcard, $query) !== false
            && !in_array($item->no_jobcard, $existingJobCards);
    })->take(5); // Limit to 5 results

    if ($jobCards->isEmpty()) {
        return response()->json(['error' => 'Job card not found'], 404);
    }

    return response()->json($jobCards->values());
}


// public function searchJobCard(Request $request)
// {
    
//     $client = new Client();
//     $url = 'http://127.0.0.1:8001/api/data-api';
//     $response = $client->request('GET',$url);
//     // dd($response);
//     $data =  json_decode($response->getBody()->getContents());

//     $query = $request->input('query');
//     $jobCards = JobCardM::where('no_jobcard', 'like', "%{$query}%")
//                 ->select('no_jobcard')
//                 ->limit(5)
//                 ->get();

//     if ($jobCards->isEmpty()) {
//         return response()->json(['error' => 'Job card not found'], 404); // Not found response
//     }

//     return response()->json($jobCards);
// }

public function getJobCardDetails(Request $request)
{
    // Validate the request
    $request->validate([
        'no_jobcard' => 'required|string',
    ]);

    // Fetch job card details
    $jobCard = JobCardM::where('no_jobcard', $request->no_jobcard)->first();

    // Check if job card exists
    if (!$jobCard) {
        return response()->json(['error' => 'Job Card not found'], 404);
    }

    return response()->json($jobCard);
}




}
