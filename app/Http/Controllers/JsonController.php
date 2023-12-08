<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JsonController extends Controller
{
    public function handle(Request $request)
    {
        // Access the JSON data from the request
        $jsonData = $request->json()->all();

        // Process the JSON data
        $jsonString = json_encode($jsonData);
        Log::info($jsonString);

        // Return a JSON response if needed
        return response()->json(['message' => 'Data received and processed']);
    }
}
