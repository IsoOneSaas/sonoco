<?php namespace App\Listeners;

use App\Events\DocumentDeleted;
use App\Models\Document\StatusModel;
use Carbon\Carbon;

class DeletedDocumentStatus
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
    public function handle(DocumentDeleted $event): void
    {
        $action = config('settings.document_status.delete');
        $now = Carbon::now()->toDateTimeString();
        $admin = auth()->user()->user_uid;

        // actualiza estado actual
        $current = $event->document->status()->latest()->first();
        $status = StatusModel::find($current->status_id);
        $status->return_by = $admin;
        $status->return_date = $now;
        $status->action_date = $now;
        $status->save();

        // Crea el estado eliminado (del registro NO se elimina)
        $status = new StatusModel();
        $status->fill([
            'document_id'   => $event->document->document_id,
            'action'        => $action,
            'action_date'   => $now,
            'return_date'   => $now,
            'action_by'     => $admin,
            'return_by'     => null,
        ])->save();
        
    // Cambiar estado en el registro del documento
    $event->document->status = $action;
    $event->document->save();        

    } // handle
} // class
