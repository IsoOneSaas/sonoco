<?php namespace App\Listeners;

use App\Events\DocumentPublished;
use App\Models\Document\StatusModel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublishDocumentStatus
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
    public function handle(DocumentPublished $event): void
    {
        $action = config('settings.document_status.publish');

        // Valida si no está publicado
        $status = StatusModel::where('document_id', $event->document->document_id)->where('action', $action)->whereNull('return_by')->first();
        if($status) {
            
            $status->return_by = auth()->user()->user_uid;
            if ( isset($event->document->forcedate) ) {
                // si es publicacion forzada
                $status->return_date = $event->document->forcedate;
                $status->action_date = $event->document->forcedate;
            } else {
                // si es publicacion normal
                $status->return_date = Carbon::now()->toDateTimeString();
            }
            $status->save();
        } // if

    } // handle
} // class
