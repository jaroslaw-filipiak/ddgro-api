<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

use App\Models\Application;

class ApplicationDataMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Application $application
    ) {
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */

    //  TODO: change the email address
    public function envelope()
    {
        return new Envelope(
            from: new Address('info@j-filipiak.pl', 'DDGRO'),
            subject: 'Zestawienie wsporników',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.application-data-mail',
            with: [
                'name' => $this->application->name_surname,
                'email' => $this->application->email,
                'phone' => $this->application->phone,
                'message' => $this->application->message,
                'pdf_url' => $this->application->pdf_url,
                
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}