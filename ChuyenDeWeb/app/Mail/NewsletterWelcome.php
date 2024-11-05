<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        Log::info('NewsletterWelcome mail class instantiated', [
            'name' => $name,
            'email' => $email
        ]);
    }

    public function build()
    {
        Log::info('Building NewsletterWelcome email', [
            'to' => $this->email
        ]);

        try {
            return $this->subject('Chào mừng bạn đến với bản tin của Web bán hàng')
                ->view('email.newsletter-welcome')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email
                ]);
        } catch (\Exception $e) {
            Log::error('Error building newsletter email', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
