<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
  /**
   * Run the migrations.
   * create channels table
   * @return void
   */
  public function up()
  {
    Schema::create('channels', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('project_id')->unsigned();
      $table->string('name', 45);
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
   * drop channels table
   * @return void
   */
  public function down()
  {
    Schema::drop('channels');
  }
}
