<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
  /**
   * Run the migrations.
   * create countries table
   * @return void
   */
  public function up()
  {
    Schema::create('countries', function (Blueprint $table) {
      $table->increments('id');
      $table->string('label', 45);
      // $table->string('iso_code', 45);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   * drop countries table
   * @return void
   */
  public function down()
  {
    Schema::drop('countries');
  }
}
