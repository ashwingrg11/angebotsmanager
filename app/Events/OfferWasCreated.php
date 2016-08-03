<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OfferWasCreated extends Event
{
  use SerializesModels;
  /**
   * [$offer_id description]
   * @var [type]
   */
  public $offer_id;
  public $to_contact_email;
  public $data;
  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($offer_id, $to_contact_email, $data)
  {
    $this->offer_id = $offer_id;
    $this->to_contact_email = $to_contact_email;
    $this->data = $data;
  }

  /**
   * Get the channels the event should be broadcast on.
   *
   * @return array
   */
  public function broadcastOn()
  {
    return [];
  }
}
