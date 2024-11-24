<?php namespace App\Events;

use App\Models\Document\RecordModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecordTracing
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public RecordModel $record;        

    /**
     * Create a new event instance.
     */
    public function __construct(RecordModel $record)
    {
        $this->record = $record;
    }

} // class
