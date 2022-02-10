<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Help extends Mailable
{
    use Queueable, SerializesModels;

    public $title_help;
    public $full_name;
    public $email_help;
    public $message_help;
    public $telefone_help;
    
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title_help, $full_name, $email_help, $message_help, $telefone_help)
    {
        $this->title_help = $title_help;
        $this->full_name = $full_name;
        $this->email_help = $email_help;
        $this->message_help = $message_help;
        $this->telefone_help = $telefone_help;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.help')->subject('Novo email do site Yah! Café');
    }
}
