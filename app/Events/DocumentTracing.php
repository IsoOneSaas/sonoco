<?php namespace App\Events;

use App\Models\Document\DocumentModel;
//use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
//use Illuminate\Broadcasting\PresenceChannel;
//use Illuminate\Broadcasting\PrivateChannel;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentTracing
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public DocumentModel $document;        

    /**
     * Create a new event instance.
     */
    public function __construct(DocumentModel $document)
    {
        $this->document = $document;
    }

} // class
