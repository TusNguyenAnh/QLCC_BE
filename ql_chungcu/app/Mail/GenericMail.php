<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $viewName;
    public $data;

    public function __construct($subjectText, $viewName, $data = [])
    {
        $this->subjectText = $subjectText;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->view($this->viewName)
            ->with($this->data);
    }
}
