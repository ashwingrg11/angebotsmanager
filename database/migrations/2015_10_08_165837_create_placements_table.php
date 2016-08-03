<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePlacementsTable extends Migration
{
  /**
   * Run the migrations.
   * create placements table
   * @return void
   */
  public function up()
  {
    Schema::create('placements', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('project_id')->unsigned();
      $table->integer('channel_id')->unsigned();
      $table->string('title', 45);
      $table->date('start_date')->default(Carbon::now()->format('Y-m-d'));
      $table->date('end_date')->nullable()->default(null);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onUpdate('cascade');
      $table->foreign('channel_id')
          ->references('id')
          ->on('channels')
          ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop placements table
   * @return void
   */
  public function down()
  {
    Schema::drop('placements');
  }
}
