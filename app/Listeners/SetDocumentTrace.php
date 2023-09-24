<?php namespace App\Listeners;

use App\Events\DocumentTracing;
use App\Models\Document\TracingModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SetDocumentTrace
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
    public function handle(DocumentTracing $event): void
    {
        $trace = new TracingModel();
        $trace->fill([
            'document_id'   => $event->document->document_id,
            'user_uid'      => auth()->user()->user_uid,
            'trace'         => ( isset($event->document->trace) ) ?
                                trans('document/document.'. $event->document->action .'.trace', [
                                'action' => config('settings.document_status.'. $event->document->action),
                                'trace'  => $event->document->trace,
                            ]) :
                            $event->document->event
                            ,
        ])->save();

    } // handle
} // class
