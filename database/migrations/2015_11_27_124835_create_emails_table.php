<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateEmailsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('emails', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('email_template_id')->unsigned()->nullable()->default(NULL);
      $table->integer('email_content_id')->unsigned();
      $table->integer('offer_id')->unsigned();
      $table->integer('offer_report_id')->unsigned()->nullable()->default(NULL);
      $table->datetime('send_date')->default(Carbon::now()->addDays(5)->format('Y-m-d H:i:s'));
      $table->enum('status', ['pending', 'cancelled', 'sent'])->default('pending');
      $table->enum('type', ['activation', 'microsite1', 'microsite2_final', 'microsite2_reminder'])->default('activation');
      $table->boolean('confirm_send')->default(0);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('email_template_id')
        ->references('id')
        ->on('email_templates')
        ->onUpdate('cascade');
      $table->foreign('email_content_id')
        ->references('id')
        ->on('email_contents')
        ->onDelete('cascade');
      $table->foreign('offer_id')
        ->references('id')
        ->on('offers')
        ->onDelete('cascade');
      $table->foreign('offer_report_id')
        ->references('id')
        ->on('offer_reports')
        // ->onDelete('cascade');
        ->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('emails');
  }
}
