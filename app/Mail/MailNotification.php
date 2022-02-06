<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $attendee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $attendee)
    {
        $this->details = $details;
        $this->attendee = $attendee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ikthimahmed@gmail.com')->subject('Room Booking Confirmed')->view('send-mail');
    }
}