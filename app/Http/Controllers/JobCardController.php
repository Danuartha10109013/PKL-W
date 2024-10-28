<?php
// app/Http/Controllers/JobCardController.php
namespace App\Http\Controllers;

use App\Models\JobCardM;
use App\Models\KomisiPenjualanM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobCardController extends Controller
{

public function searchJobCard(Request $request)
{
    $query = $request->input('query');
    $jobCards = JobCardM::where('no_jobcard', 'like', "%{$query}%")
                ->select('no_jobcard')
                ->limit(5)
                ->get();

    if ($jobCards->isEmpty()) {
        return response()->json(['error' => 'Job card not found'], 404); // Not found response
    }

    return response()->json($jobCards);
}

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
