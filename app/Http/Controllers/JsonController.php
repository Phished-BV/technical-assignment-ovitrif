<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        $orderData = $this->extractOrderData($messageBody);
        $this->logOrderData($orderData);
        // create order entity in database
        // $orderEntity = Order::create($orderData);

        // Return a JSON response if needed
        return response()->json(['message' => 'Data received and processed']);
    }

    private function logOrderData(array $orderData)
    {
        $orderDataString = json_encode($orderData);
        Log::info($orderDataString);
    }

    private function extractOrderData($emailBody): array
    {
        $total = $this->extractTotal($emailBody);
        $address = $this->extractAddress($emailBody);
        $recipient = $this->extractRecipient($emailBody);

        return [
            'total' => $total,
            'address' => $address,
            'recipient' => $recipient,
        ];
    }

    private function extractTotal($messageBody): int
    {
        $pattern = '/Total:\s+\$(\d+);/';
        if (preg_match($pattern, $messageBody, $matches)) {
            return (int)$matches[1];
        } else {
            return 0;
        }
    }

    private function extractAddress($messageBody): string
    {
        $pattern = '/Address:\s(.*?);/';
        if (preg_match($pattern, $messageBody, $matches)) {
            return trim($matches[1]);
        } else {
            return '';
        }
    }

    private function extractRecipient($messageBody): string
    {
        $pattern = '/Recipient:\s(.*?)(?=\.)/';
        if (preg_match($pattern, $messageBody, $matches)) {
            return trim($matches[1]);
        } else {
            return '';
        }
    }
}
