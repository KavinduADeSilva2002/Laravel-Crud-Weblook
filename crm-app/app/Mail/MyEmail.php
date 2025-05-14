<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $messageText;
    public int $invoice_id;

    public function __construct(string $messageText, int $invoice_id)
    {
        $this->messageText = $messageText;
        $this->invoice_id = $invoice_id;
    }

    public function build(): self
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Invoice Payment Request')
                    ->view('mail.test-email') // Updated path to match actual file location
                    ->with([
                        'messageText' => $this->messageText,
                        'invoice_id' => $this->invoice_id,
                    ]);
    }
}
