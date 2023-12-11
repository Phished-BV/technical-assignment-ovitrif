<?php

namespace App\Http\Controllers;

use App\Events\OrderMailReceived;
use App\Mail\OrderMail;
use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function handle(Request $request)
    {
        $jsonData = $request->json()->all();

        // Filter out non-order mails
        if (!$this->isOrderMail($jsonData)) {
            return response()->json(['message' => 'Not an order mail']);
        }

        $emailBody = $jsonData['Snippet'];
        event(new OrderMailReceived($emailBody));

        return response()->json(['message' => 'Data received and processed']);
    }

    private function isOrderMail($jsonData): bool
    {
        $targetMailbox = $jsonData['To'][0]['Address'];
        return $targetMailbox === OrderMail::MAILBOX;
    }
}
