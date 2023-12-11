<?php

namespace App\Listeners;

use App\Events\OrderMailReceived;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessOrderMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderMailReceived $event): void
    {
        $orderData = $this->extractOrderData($event);

        // Record order entity in database
        $orderEntity = Order::create($orderData);

        // Log order info
        Log::info(json_encode($orderData));
        Log::info('Registered order with id: ' . $orderEntity->id, ['id' => $orderEntity->id]);
    }

    private function extractOrderData($event): array
    {
        $emailBody = $event->emailBody;

        $total = $this->extractTotal($emailBody);
        $address = $this->extractAddress($emailBody);
        $recipient = $this->extractRecipient($emailBody);
        $publicId = $this->extractPublicId($emailBody);

        return [
            'total' => $total,
            'address' => $address,
            'recipient' => $recipient,
            'public_id' => $publicId,
            'recipient_email' => $event->emailAddress,
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
