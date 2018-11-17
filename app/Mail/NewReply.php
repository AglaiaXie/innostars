<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReply extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $title;

    /**
     * Create a new message instance.
     *
     * @param string $title
     * @param string $name
     */
    public function __construct(string $title, string $name)
    {
        $this->title = $title;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New InnoSTARS message reply from ' . $this->name . ': ' . $this->title)
            ->markdown('vendor.notifications.email', [
                "level" => "default",
                "greeting" => "Dear User,",
                "introLines" => [
                    "You have a new message reply in you InnoSTARS account. Log in to read the message."
                ],
                "actionText" => "Access My Account",
                "actionUrl" => url('https://innostars2018.uschinainnovation.org/login'),
                "outroLines" => ['Regards,', 'InnoSTARS'],
            ]);
    }
}
