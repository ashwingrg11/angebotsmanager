<?php

namespace App\Providers;

use App\Events\OfferWasCreated;
use App\Handlers\Events\SendOfferConfirmation;
use App\Events\OfferExtension;
use App\Handlers\Events\SendOfferExtensionEmail;
use App\Handlers\Events\CreateActivationEmail;
use App\Handlers\Events\CreateMicrosite1Emails;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    'App\Events\SomeEvent' => [
      'App\Listeners\EventListener',
    ],
    OfferWasCreated::class => [
      CreateActivationEmail::class,
      CreateMicrosite1Emails::class,
    ],
    OfferExtension::class => [
      SendOfferExtensionEmail::class
    ]
  ];

  /**
   * Register any other events for your application.
   *
   * @param  \Illuminate\Contracts\Events\Dispatcher  $events
   * @return void
   */
  public function boot(DispatcherContract $events)
  {
    parent::boot($events);

    //
  }
}
