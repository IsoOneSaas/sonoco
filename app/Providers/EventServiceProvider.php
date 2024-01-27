<?php namespace App\Providers;

use App\Events\DocumentBack;
use App\Events\DocumentDeleted;
use App\Events\DocumentPublished;
use App\Events\DocumentSent;
use App\Events\DocumentSwitch;
use App\Events\DocumentTracing;
use App\Events\EmailSent;
use App\Events\EmailDocumentEvent;
use App\Events\EmailDueEvent;

use App\Listeners\BackDocumentStatus;
use App\Listeners\DeletedDocumentStatus;
use App\Listeners\ChangeDocumentStatus;
use App\Listeners\PublishDocumentStatus;
use App\Listeners\SwitchDocumentStatus;
use App\Listeners\SetDocumentTrace;
use App\Listeners\SendNotification;
use App\Listeners\DocumentNotification;
use App\Listeners\DueNotification;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        DocumentSent::class => [
            ChangeDocumentStatus::class,                      
        ],
        DocumentBack::class => [
            BackDocumentStatus::class
        ],
        DocumentPublished::class => [
            PublishDocumentStatus::class
        ],
        DocumentSwitch::class => [
            SwitchDocumentStatus::class
        ],        
        DocumentDeleted::class => [
            DeletedDocumentStatus::class,
        ],                                
        DocumentTracing::class => [
            SetDocumentTrace::class
        ],
        EmailSent::class => [
            SendNotification::class,
        ],
        EmailDocumentEvent::class => [
            DocumentNotification::class,
        ],
        EmailDueEvent::class => [
            DueNotification::class,
        ],                             
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
