<?php

namespace App\Handlers\Events;

use Mail;
use App\Events\OfferWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOfferConfirmation
{
  /**
   * Create the event handler.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  OfferWasCreated  $event
   * @return void
   */
  public function handle(OfferWasCreated $event)
  {
    $data = $event->data;
    Mail::send('emails.offers.offer_was_created', ['data' => $data], function ($message) use ($event) {
      // $message->from('ashwin.gurung@cloudyfox.com', 'Ashwin Gurung');
      // $message->from('chrisw@journal-international.de', 'Chris Wieckowski');
      $message->to($event->to_contact_email)->subject('New Offer Created!');
    });
  }
}
