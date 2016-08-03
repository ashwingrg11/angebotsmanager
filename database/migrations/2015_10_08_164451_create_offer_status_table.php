<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferStatusTable extends Migration
{
  /**
   * Run the migrations.
   * create offer_status table
   * @return void
   */
  public function up()
  {
    Schema::create('offer_status', function (Blueprint $table) {
      $table->increments('id');
      $table->string('label', 90);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   * drop offer_status table
   * @return void
   */
  public function down()
  {
    Schema::drop('offer_status');
  }
}
