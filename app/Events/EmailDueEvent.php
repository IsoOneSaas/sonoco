<?php namespace App\Events;

use App\Models\Set\UserModel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailDueEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */   
    public UserModel $user;

    /**
     * Create a new event instance.
     */
    public function __construct(UserModel $user)
    {
        $this->user = $user;
    }

} // class
