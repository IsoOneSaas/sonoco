<?php namespace App\Listeners;

use App\Events\EmailDocumentEvent;
use App\Mail\NewDocumentAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DocumentNotification
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
    public function handle(EmailDocumentEvent $event): void
    {
        Mail::to($event->user->email)->queue(
            new NewDocumentAlert($event->setting, $event->user->name)
        );
    } // handle
} // class
