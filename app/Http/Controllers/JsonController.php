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
        $messageBody = $jsonData['Snippet'];
        $jsonString = $this->parseEmail($messageBody);
        // $jsonString = $this->parseSummary($jsonData);
        // $keys = array_keys($jsonData);
        // $keysString = implode(', ', $keys);
        Log::info($jsonString);

        // Return a JSON response if needed
        return response()->json(['message' => 'Data received and processed']);
    }

    // TODO return an associative array of the order data
    private function parseEmail($emailBody): string
    {
        $total = $this->extractTotal($emailBody);
        return "Total Value: $total";
    }

    private function extractTotal($messageBody): int
    {
        $pattern = '/Total:\s+\$(\d+);/';
        if (preg_match($pattern, $messageBody,$matches)) {
            return (int) $matches[1];
        } else {
            return 0;
        }
    }
}
