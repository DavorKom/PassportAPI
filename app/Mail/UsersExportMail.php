<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UsersExportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $filename;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

        return $this->markdown('emails.users.export')
                    ->attach($storagePath . "$this->filename.pdf")
                    ->attach($storagePath . "$this->filename.xlsx");
    }
}
