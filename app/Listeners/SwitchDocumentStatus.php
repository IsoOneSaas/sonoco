<?php namespace App\Listeners;

use App\Events\DocumentSwitch;
use App\Models\Document\DocumentModel;
use App\Models\Document\StatusModel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SwitchDocumentStatus
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
    public function handle(DocumentSwitch $event): void
    {
        $now = Carbon::now()->toDateTimeString();
        $actionP = config('settings.document_status.publish');
        $actionO = config('settings.document_status.obsolete');
        $current = $event->document->status()->latest()->first();

        if( $current->action == $actionP ) {
            // Actual publicado -> obsoleto

            // Crear nuevo estado
            $status = new StatusModel();
            $status->fill([
                'document_id'   => $event->document->document_id,
                'action'        => $actionO,
                'action_date'   => $now,
                'return_date'   => $now,
                'action_by'     => auth()->user()->user_uid,
                'return_by'     => null,
            ])->save();

            // actualizar tabla de documentos
            $event->document->status = $actionO;
            $event->document->save();        
        } else {
            // Actual obsoleto -> publicado

            // Modificar registro anterior
            $current->return_date = $now;
            $current->return_by = auth()->user()->user_uid;
            $current->save();

            // Replicar el registro de publicado
            $before = StatusModel::where('document_id', $event->document->document_id)->where('action', $actionP)->orderBy('created_at', 'desc')->first();
            $status = StatusModel::find($before->status_id);
            $new = $status->replicate();
            $new->save();

            // actualizar tabla de documentos
            $event->document->status = $actionP;
            $event->document->save();            
        }

    } // handle
} // class
