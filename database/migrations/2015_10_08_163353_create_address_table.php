<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
  /**
   * Run the migrations.
   * create address table
   * @return void
   */
  public function up()
  {
    Schema::create('address', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('country_id')->unsigned()->nullable()->default(NULL);
      $table->string('street1', 45)->nullable()->default(NULL);
      $table->string('street2', 45)->nullable()->default(NULL);
      $table->string('street3', 45)->nullable()->default(NULL);
      $table->string('post_code', 45)->nullable()->default(NULL);
      $table->string('city', 45)->nullable()->default(NULL);
      $table->tinyInteger('county')->nullable()->default(NULL);
      $table->string('state', 45)->nullable()->default(NULL);
      // $table->string('country', 45)->nullable()->default(NULL);
      $table->text('display_address')->nullable()->default(NULL);
      $table->foreign('country_id')
          ->references('id')
          ->on('countries')
          ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop address table
   * @return void
   */
  public function down()
  {
    Schema::drop('address');
  }
}
