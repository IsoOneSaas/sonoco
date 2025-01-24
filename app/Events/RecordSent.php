<?php namespace App\Events;

use App\Models\Document\RecordModel;
use App\Models\Set\UserModel;
//use App\Models\Document\SettingModel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecordSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public RecordModel $record;    
    public UserModel $user;
    public $settings;

    /**
     * Create a new event instance.
     */
    public function __construct(RecordModel $record, UserModel $user, $settings)
    {
        $this->record = $record;
        $this->user = $user;
        $this->settings = $settings;
    }

} // class
