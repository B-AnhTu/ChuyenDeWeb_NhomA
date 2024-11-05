<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        try {
            Log::info('Building email with data', ['data' => $this->data]);

            return $this->from(env('MAIL_FROM_ADDRESS'))
                ->subject('Tin nhắn mới từ khách hàng')
                ->view('email.contact')
                ->with([
                    'name' => $this->data['name'],
                    'email' => $this->data['email'],
                    'userMessage' => $this->data['userMessage']
                ]);
        } catch (\Exception $e) {
            Log::error('Error building email: ' . $e->getMessage());
            throw $e;
        }
    }
}
