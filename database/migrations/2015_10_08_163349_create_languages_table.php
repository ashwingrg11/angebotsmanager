<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
  /**
   * Run the migrations.
   * create languages table
   * @return void
   */
  public function up()
  {
    Schema::create('languages', function (Blueprint $table) {
      $table->increments('id');
      $table->string('label', 45);
      // $table->string('iso', 45);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   * drop languages table
   * @return void
   */
  public function down()
  {
    Schema::drop('languages');
  }
}
