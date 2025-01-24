<?php namespace App\Listeners;

use App\Events\RecordSent;
use App\Mail\NewRecordNotice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RecordNotification
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
    public function handle(RecordSent $event): void
    {
        Mail::to($event->user->email)->queue(
            new NewRecordNotice($event->user, $event->record, $event->settings)
        );
    } // handle
} // class
