<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    //public $file;
    public function __construct($data)
    {
        $this->data = $data;
     //   $this->file = $file;
    }
    public function build()
    {
        return $this->view('mail');
    }
}