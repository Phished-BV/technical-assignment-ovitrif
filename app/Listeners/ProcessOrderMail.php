<?php

namespace App\Listeners;

use App\Events\OrderMailReceived;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessOrderMail implements ShouldQueue
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
        // Parse order details from email
        $orderData = $this->extractOrderData($event);

        // Record order entity in database
        Order::create($orderData);
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
        $pattern = '/Recipient:\s(.*?);/';
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
