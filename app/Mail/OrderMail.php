<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    const MAILBOX = 'orders@example.com';
    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(array $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(address: 'order@example.com', name: 'Order'),
            to: self::MAILBOX,
            subject: 'Order ' . $this->order['id'] . ' Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.order',
            with: [
                'id' => $this->order['id'],
                'address' => $this->order['address'],
                'recipient' => $this->order['recipient'],
                'total' => $this->order['total'],
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
