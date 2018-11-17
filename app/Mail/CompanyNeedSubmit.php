<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyNeedSubmit extends Mailable
{
    use Queueable, SerializesModels;

    protected $day;

    /**
     * Create a new message instance.
     *
     * @param int $day
     */
    public function __construct(int $day)
    {
        $this->day = $day;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Please Complete Your 2018 InnoSTARS Application Before Deadline')
            ->markdown('vendor.notifications.email', [
                "level" => "success",
                "greeting" => "Dear Contestant,",
                "introLines" => [
                    "you only have {$this->day} days left to complete your 2018 InnoSTARS application. Application after deadline will no longer be accepted and we cannot grant any extensions. You can log into your account by clicking the button below"
                ],
                "actionText" => "Access My Account",
                "actionUrl" => url('https://innostars2018.uschinainnovation.org/login'),
                "outroLines" => ['Regards,', 'InnoSTARS'],
            ]);
    }
}
