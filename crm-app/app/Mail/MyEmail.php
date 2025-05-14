<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $messageText;

    /**
     * Create a new message instance.
     *
     * @param string $messageText
     */
    public function __construct(string $messageText)
    {
        $this->messageText = $messageText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject('Test Mail from Laravel')
                    ->view('mail.test-email')
                    ->with('messageText', $this->messageText);
    }
}
