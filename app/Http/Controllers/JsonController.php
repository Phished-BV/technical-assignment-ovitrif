<?php

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JsonController extends Controller
{
    public function handle(Request $request)
    {
        // Access the JSON data from the request
        $jsonData = $request->json()->all();

        if (!$this->isOrderMail($jsonData)) {
            return response()->json(['message' => 'Not an order mail']);
        }

        // Process Order Email
        $messageBody = $jsonData['Snippet'];
        $orderData = $this->extractOrderData($messageBody);
        $this->logOrderData($orderData);

        // Record order entity in database
        $orderEntity = Order::create($orderData);

        Log::info('Registered order with id: ' . $orderEntity->id, ['id' => $orderEntity->id]);

        // Return a JSON response if needed
        return response()->json(['message' => 'Data received and processed']);
    }

    private function isOrderMail($jsonData): bool
    {
        $targetMailbox = $jsonData['To'][0]['Address'];
        return $targetMailbox === OrderMail::MAILBOX;
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
        $publicId = $this->extractPublicId($emailBody);

        return [
            'total' => $total,
            'address' => $address,
            'recipient' => $recipient,
            'public_id' => $publicId,
        ];
    }

    private function extractTotal($emailBody): int
    {
        $pattern = '/Total:\s+\$(\d+);/';
        if (preg_match($pattern, $emailBody, $matches)) {
            return (int)$matches[1];
        } else {
            return 0;
        }
    }

    private function extractAddress($emailBody): string
    {
        $pattern = '/Address:\s(.*?);/';
        if (preg_match($pattern, $emailBody, $matches)) {
            return trim($matches[1]);
        } else {
            return '';
        }
    }

    private function extractRecipient($emailBody): string
    {
        $pattern = '/Recipient:\s(.*?)(?=\.)/';
        if (preg_match($pattern, $emailBody, $matches)) {
            return trim($matches[1]);
        } else {
            return '';
        }
    }

    private function extractPublicId($emailBody): string
    {
        $pattern = '/id:\s*(\d+)\./';
        if (preg_match($pattern, $emailBody, $matches)) {
            return trim($matches[1]);
        } else {
            return '';
        }
    }
}
