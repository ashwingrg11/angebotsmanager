<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('email_templates', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('project_id')->unsigned();
      $table->integer('email_content_id')->unsigned();
      $table->enum('type', ['activation', 'offer_extension', 'microsite1', 'microsite2_final', 'microsite2_reminder'])->default('activation');
      $table->enum('language', ['en', 'de'])->default('en');
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('project_id')
        ->references('id')
        ->on('projects')
        ->onUpdate('cascade');
      $table->foreign('email_content_id')
        ->references('id')
        ->on('email_contents')
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
    Schema::drop('email_templates');
  }
}
