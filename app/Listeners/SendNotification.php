<?php namespace App\Listeners;

use App\Events\EmailSent;
use App\Mail\Responsible;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailSent $event): void
    {
        //Log::debug(['OUTPUT USER' => $event->user->toArray(), 'DOC' => $event->document->toArray()]);
        Mail::to($event->user->email)->queue(
            new Responsible($event->user->name, $event->document->name, $event->document->action, $event->document->date, $event->document->link, $event->document->event)
        );
    } // handle
} // class
