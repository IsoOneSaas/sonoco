<?php namespace App\Traits\Document;

use App\Models\Document\DocumentModel;
use App\Models\Document\ForwardModel;

trait ControlDocumentsTrait {

    protected $colorDefault = 2;

    public function confirmCheckIn2($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $user = auth()->user();
        $document = DocumentModel::find($id);
        $current = $document->status()->latest()->first();

        if( in_array($current->action, config('settings.document_status_users') ) ) {

            // Determinar total        
            $total = ForwardModel::where('document_id', $id)->where('action', $current->action)->count();
            // Determinar chequeados
            $checked = ForwardModel::where('document_id', $id)->where('action', $current->action)->where('checked', 1)->count();

            if( $user->hasRole('MASTER') ) {
                return true;
            } elseif( $user->hasRole('ADMIN')  ) { // FIXME: qué pasa si el administrador actuaa como usuario CONSULTAR!!
                return ( $checked > 0 ) ? true : false;
            } else {
                // FIXME: código colores
                return ( $checked == $total ) ? true : false;
            }
        } else {
            return true;
        }
    } // confirm Method

    public function confirmCheckIn($hash)
    {
        $id = $this->tool->getIdHash($hash);
        $user = auth()->user();
        $document = DocumentModel::find($id);
        $current = $document->status()->latest()->first();
        $profile = ($document->switch == 1) ? true : false;

        if( in_array($current->action, config('settings.document_status_users') ) ) {

            // Determinar total        
            $total = ForwardModel::where('document_id', $id)->where('action', $current->action)->count();
            // Determinar chequeados
            $checked = ForwardModel::where('document_id', $id)->where('action', $current->action)->where('checked', 1)->count();

            if( $user->hasRole('MASTER') ) {
                return true;
            } elseif( $user->hasRole('ADMIN')  ) {
                if( $profile ) {
                    return ( $checked == $total ) ? true : false;
                } else {
                    return ( $checked > 0 ) ? true : false;
                }
            } else {
                return ( $checked == $total ) ? true : false;
            }
        } else {
            return true;
        }
    } // confirm Method    
    

} // trait