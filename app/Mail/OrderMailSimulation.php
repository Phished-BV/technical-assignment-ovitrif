<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMailSimulation extends Mailable
{
    use Queueable, SerializesModels;

    const MAILBOX = 'orders@example.com';
    public array $order;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->order = [
            'id' => fake()->numberBetween(10000, 50000),
            'recipient_email' => fake()->email(),
            'address' => fake()->address(),
            'recipient' => fake()->name(),
            'total' => fake()->numberBetween(100, 5000),
        ];

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(address: $this->order['recipient_email'], name: $this->order['recipient']),
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
