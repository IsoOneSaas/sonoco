<?php namespace App\Listeners;

use App\Events\RecordTracing;
use App\Models\Document\TracingRecordModel;
use Log;

class SetRecordTrace
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
    public function handle(RecordTracing $event): void
    {
        $trace = new TracingRecordModel();
        //Log::debug(['TRACE' => $event->record->toArray()]);
        $trace->fill([
            'record_id' => $event->record->record_id,
            'user_uid'  => $event->record->user_uid,
            'trace'     => ( isset($event->record->trace) ) ?
                                trans('document/record.'. $event->record->action .'.trace', [
                                'action' => config('settings.document_status.'. $event->record->action),
                                'trace'  => $event->record->trace,
                            ]) :
                            $event->record->event
                            ,
        ])->save();

    } // handle
} // class
