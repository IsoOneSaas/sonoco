<?php namespace App\Mail;

//use App\Models\Document\SettingModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRecordNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $set;
    public $userName;
    public $recordName;
    public $recordLink;
    public $recordSign;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $record, $set)
    {
        $this->userName = $user->name;
        $this->recordName = $record->name;
        $this->recordLink = $record->link;
        $this->recordSign = $record->sign;
        $this->set = $set;

        // Verificar si agrega copia oculta
        if (!empty($this->set['bcc_edit'])) {
            $this->bcc($this->set['bcc_edit']);
        }
        
        // Verificar si agrega reply to
        if (!empty($this->set['reply_to_edit'])) {
            $this->replyTo($this->set['reply_to_edit']);
        }       

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->set['subject_edit'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->markdown('emails.notice_record')
            ->from( $this->set['from_email_edit'], $this->set['from_name_edit'])
            ->subject($this->set['subject_edit']);
    }

} // class
