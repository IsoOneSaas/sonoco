<?php namespace App\Events;

use App\Models\Document\DocumentModel;
use App\Models\Set\UserModel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public DocumentModel $document;    
    public UserModel $user;

    /**
     * Create a new event instance.
     */
    public function __construct(DocumentModel $document, UserModel $user)
    {
        $this->document = $document;
        $this->user = $user;
    }

} // class
