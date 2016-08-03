<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisabledOffersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('disabled_offers', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('offer_id')->unsigned();
      $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');
      $table->foreign('offer_id')
          ->references('id')
          ->on('offers')
          ->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::drop('disabled_offers');
  }
}
