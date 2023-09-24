<?php namespace App\Listeners;

use App\Classes\ToolsClass;
use App\Events\DocumentBack;
use App\Models\Document\ForwardModel;
use App\Models\Document\StatusModel;
//use Carbon\Carbon;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BackDocumentStatus
{

    private $tool;

    /**
     * Create the event listener.
     */
    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }

    /**
     * Handle the event.
     */
    public function handle(DocumentBack $event): void
    {
        $currentStatus = $event->document->status()->latest()->first();
        $previous = $this->tool->getPreviousDocumentAction($currentStatus->action);
        //Log::debug(['CURRENT STATUS' => $currentStatus->action, 'CURRENT ID' => $currentStatus->status_id]);

        if( $previous != '' ) {

            // ELIMINAR EL ACTUAL ESTADO
            $result = StatusModel::destroy($currentStatus->status_id); 

            if( $result) {
                // ACTULIZA ESTADO ANTERIOR
                $newStatus = $event->document->status()->latest()->first();
                //Log::debug(['PREVIOUS STATUS' => $newStatus->action, 'PREVIOUS ID' => $newStatus->status_id]);
                $newStatus->return_by = null;
                $newStatus->return_date = $newStatus->created_at;
                $newStatus->save();

                // ACTUALIZA FOWARDS CHEQUEADOS
                ForwardModel::where('document_id', $event->document->document_id)->where('action', $newStatus->action)->update(['checked' =>  0]);

                // CAMBIAR ESTADO AL Registro del documento
                $event->document->status = $newStatus->action;
                $event->document->save();
            } else {
                Log::info('Cambio de estado (back) para el documento '. $event->document->document_id .' no pudo ser realizado.');
            }

        } else {
            Log::info('Cambio de estado (back) para el documento '. $event->document->document_id .' no pudo ser realizado.');
        } // if

    } // handle
} // class

