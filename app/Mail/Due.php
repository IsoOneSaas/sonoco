<?php namespace App\Mail;

use App\Models\Document\SettingModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Due extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $documents;
    public $set;
    public $sign;
    public $message;
    //public $path;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $documents)
    {
        $this->set = SettingModel::find(1)->settings;
        $this->name = $name;
        $this->documents = $documents;
        $this->sign = $this->set['signature_edit'];
        $this->message = 'Los siguientes documentos en proceso requieren de su pronta gestión:';
        //$this->path = route('documents.control.manage.edit') .'/user';


        // Verificar si agrega copia oculta
        if (!empty($this->set['bcc_edit'])) {
            $this->bcc($this->set['bcc_edit']);
        }
        
        // Verificar si agrega reply to
        if (!empty($this->set['reply_to_edit'])) {
            $this->replyTo($this->set['reply_to_edit']);
        }
        
        // agregar confirmación de lectura FIXME: Undefined array key 0
        //if ( $this->set['confirm_reading_edit'] ) {
            //$this->withSwiftMessage()->getHeaders()->addTextHeader('X-Confirm-Reading-To', $this->set['from_email_edit']);
            // $this->withSwiftMessage(function ($message) {
            //     $message->getHeaders()->addTextHeader('X-Confirm-Reading-To', $this->set['from_email_edit']);
            // });
        //}        

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
        return $this->markdown('emails.alert_due')
            ->from( $this->set['from_email_edit'], $this->set['from_name_edit'])
            ->subject($this->set['subject_edit']);
    }

} // class
