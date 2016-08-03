<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationPackageTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('communication_package', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('project_id')->unsigned();
      $table->integer('activation')->unsigned()->default(0);
      $table->integer('microsite1_monthly')->unsigned()->default(0);
      $table->integer('microsite2_final')->unsigned()->default(0);
      $table->integer('microsite2_reminder')->unsigned()->default(0);
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
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('communication_package');
  }
}
