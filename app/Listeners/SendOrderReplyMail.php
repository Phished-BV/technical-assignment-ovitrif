<?php

namespace App\Listeners;

use App\Events\OrderReplied;
use App\Mail\OrderReplyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderReplyMail implements ShouldQueue
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
    public function handle(OrderReplied $event): void
    {
        Mail::send(new OrderReplyMail([
            'message' => $event->order->reply,
            'id' => $event->order->public_id,
        ]));
    }
}
