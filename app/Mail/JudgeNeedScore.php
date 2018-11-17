<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JudgeNeedScore extends Mailable
{
    use Queueable, SerializesModels;

    protected $day;
    protected $name;

    /**
     * Create a new message instance.
     *
     * @param int $day
     * @param string $name
     */
    public function __construct(int $day, string $name)
    {
        $this->day = $day;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Please Review and Score 2018 InnoSTARS Application {$this->name} Before Deadline")
            ->markdown('vendor.notifications.email', [
                "level" => "success",
                "greeting" => "Dear Judge,",
                "introLines" => [
                    "Thank you for participating in the 2018 InnoSTARS competition application screening process. " .
                    "I am sincerely grateful for your help! Now you only have {$this->day} day(s) left to finish " .
                    "scoring 2018 InnoSTARS contestants, please log into your account by clicking the button below"
                ],
                "actionText" => "Access My Account",
                "actionUrl" => url('https://innostars2018.uschinainnovation.org/login'),
                "outroLines" => ['Regards,', 'InnoSTARS'],
            ]);
    }
}
