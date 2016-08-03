<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralOfferTypeMasterlistTable extends Migration
{
  /**
   * Run the migrations.
   * create general_offer_type_masterlist table
   * @return void
   */
  public function up()
  {
    Schema::create('general_offer_type_masterlist', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('project_id')->unsigned();
      $table->string('label', 45);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop general_offer_type_masterlist table
   * @return void
   */
  public function down()
  {
    Schema::drop('general_offer_type_masterlist');
  }
}
