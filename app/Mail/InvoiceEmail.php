<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $invoice;
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Wajubodota',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {


        return new Content(
            view: 'mail.mail',
            with: [
                'order_date' => $this->invoice['created_at'],
                'start_date' => $this->invoice['start_date'],
                'end_date' => $this->invoice['end_date'],
                'tanggal_pengambilan' => $this->invoice['tanggal_pengambilan'],
                'full_payment_status' => $this->invoice['full_payment_status'],
                'user_name' => $this->invoice['user_name'],
                'id' => $this->invoice['id'],
                'total_price' => $this->invoice['total_price'],
                'customer_name' => $this->invoice['customer_name'],
                'belum_dibayar' => $this->invoice['belum_dibayar'],
                'down_payment' => $this->invoice['down_payment'],
                'ket_edit' => $this->invoice['ket_edit'],
                'order_detail' => $this->invoice['order_detail'],
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
