<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanySubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('2018 InnoSTARS Application Submitted')
            ->markdown('vendor.notifications.email', [
                "level" => "success",
                "greeting" => "Dear Contestant,",
                "introLines" => [
                    "Congratulations! Your application has been successfully submitted to our system. You will receive further notifications regarding your application status in the next 2-6 weeks. In the meanwhile, you can always log into your account by clicking the button below"
                ],
                "actionText" => "Access My Account",
                "actionUrl" => url('https://innostars2018.uschinainnovation.org/login'),
                "outroLines" => ['Regards,', 'InnoSTARS'],
            ]);
    }
}
