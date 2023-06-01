<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;


    private $newPassword;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($passwd)
    {
        $this->newPassword = $passwd;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $newpass=$this->newPassword;
        return $this ->view('sendmail.changepassword',compact('newpass'))->subject("CAP NHAT MAT KHAU");
    }
}
