<?php namespace App\Events;

use App\Models\Document\SettingModel;
use App\Models\Set\UserModel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailDocumentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public SettingModel $setting;    
    public UserModel $user;

    /**
     * Create a new event instance.
     */
    public function __construct(SettingModel $setting, UserModel $user)
    {
        $this->setting = $setting;
        $this->user = $user;
    }

} // class
