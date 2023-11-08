<?php namespace App\Listeners;

use App\Events\DocumentSent;
use App\Models\Document\ForwardModel;
use App\Models\Document\StatusModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChangeDocumentStatus
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
    public function handle(DocumentSent $event): void
    {
        $key = 'create';
        $ok = false;
        $array = config('settings.document_status');
        $date = null;
        $limited = false;
        $roled = false;
        $current = $event->document->status()->latest()->first();
        if ($current) {
            $previous = $current->action;
            // Determina status futuro
            foreach($array as $clave=>$valor) {
                if($valor == $current->action) {
                    $ok = true;
                } elseif ($ok) {
                    $key = $clave;
                    break;
                }
            } // foreach

            // Actualiza actual status
            $status = StatusModel::find($current->status_id);            
            $status->return_date = Carbon::now()->toDateTimeString();
            $status->return_by = auth()->user()->user_uid;
            //Log::debug("previous status: ". $previous .' | actual status: '. $array[$key]);
            if( $previous != config('settings.document_status.create') ) {
                $status->action_date = Carbon::now()->toDateTimeString();
            }            
            $status->save();
            //Log::debug(['ACTUALIZACION DEL ESTADO' => $previous]);


            // Obtiene fecha de delivery
            $forward = ForwardModel::where('document_id', $event->document->document_id)->where('action', $array[$key])->first();
            $date = ($forward) ? $forward->deadline : null;
            // si se trata de un usuario y está en aprobación
            //$limited = ( $current->action == config('settings.document_status.approve') ) ? true : false;
            //$roled = ( Auth::user()->role == 'USER') ? true : false;
            $limited = ( $array[$key] == config('settings.document_status.publish') ) ? true : false;            
        } // if $current

        //Log::debug(['LIMITED' => $limited, 'ROLED' => $roled]);
        //if( $limited && $roled ) {
        // if( $limited ) {
        //     // La publicación debe hacerla el administrador
        //     Log::info(' La publicación debe hacerla el administrador');
        // } else {
        if( !$limited ) {            
            // Crea nuevo status
            $status = new StatusModel();
            //Log::debug(['CREACION DEL ESTADO' => $array[$key]]);
            $status->fill([
                'document_id'   => $event->document->document_id,
                'action'        => $array[$key],
                'action_date'   => Carbon::now()->toDateTimeString(),
                'action_by'     => auth()->user()->user_uid,
                'delivery_date' => $date,
                'return_by'     => null,
            ])->save();

            // Cambiar estado en el registro del documento
            $event->document->status = $array[$key];
            $event->document->save();
        } else {
            Log::info('Estado '. $array[$key] .' no fue creado para id='. $event->document->document_id);
        }

    } // handle
} // class
