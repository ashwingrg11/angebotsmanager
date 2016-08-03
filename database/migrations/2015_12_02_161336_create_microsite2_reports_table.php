<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMicrosite2ReportsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('microsite2_reports', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('offer_report_id')->unsigned();
      $table->text('question1')->nullable()->default(NULL);
      $table->text('question2')->nullable()->default(NULL);
      $table->text('question3')->nullable()->default(NULL);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('offer_report_id')
          ->references('id')
          ->on('offer_reports')
          ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('microsite2_reports');
  }
}
