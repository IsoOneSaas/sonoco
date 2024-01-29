<?php namespace App\Listeners;

use App\Events\EmailDueEvent;
use App\Mail\Due;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DueNotification
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
    public function handle(EmailDueEvent $event): void
    {
        //Log::debug(['OUTPUT USER' => $event->user->toArray(), 'DOC' => $event->document->toArray()]);
        Mail::to($event->user->email)->queue(
            new Due($event->user->name, $event->user->content, $event->user->adminName, $event->user->adminEmail, $event->user->role, $event->user->documents)
        );
    } // handle
} // class
