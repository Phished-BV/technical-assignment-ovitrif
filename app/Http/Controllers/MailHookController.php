<?php

namespace App\Http\Controllers;

use App\Events\OrderMailReceived;
use App\Mail\OrderMailSimulation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MailHookController extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $jsonData = $request->json()->all();

        // Filter out non-order mails
        if (!$this->isOrderMail($jsonData)) {
            return response()->json(['message' => 'Not an order mail']);
        }

        $emailBody = $jsonData['Snippet'];
        $emailAddress = $jsonData['From']['Address'];

        event(new OrderMailReceived($emailBody, $emailAddress));

        return response()->json(['message' => 'Data received and processed']);
    }

    private function isOrderMail($jsonData): bool
    {
        $targetMailbox = $jsonData['To'][0]['Address'];
        return $targetMailbox === OrderMailSimulation::MAILBOX;
    }
}
