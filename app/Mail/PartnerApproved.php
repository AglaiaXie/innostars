<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PartnerApproved extends Mailable
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
        return $this->subject('2018 InnoSTARS Application Confirmation')
            ->markdown('vendor.notifications.email', [
                "level" => "default",
                "greeting" => "Dear Partner,",
                "introLines" => [
                    "Congratulations! You have been approved to be the partner for the 2018 InnoSTARS Competition. You can access your account by clicking the button below."
                ],
                "actionText" => "Access My Account",
                "actionUrl" => url('https://innostars2018.uschinainnovation.org/login'),
                "outroLines" => ['Regards,', 'InnoSTARS'],
            ]);
    }
}
