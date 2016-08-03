<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailContentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('email_contents', function (Blueprint $table) {
      $table->increments('id');
      $table->string('sender_email')->nullable()->default(NULL);
      $table->string('receiver_email')->nullable()->default(NULL);
      $table->text('cc_emails')->nullable()->default(NULL);
      $table->string('subject')->nullable()->default(NULL);
      $table->text('content')->nullable()->default(NULL);
      $table->text('attachments')->nullable()->default(NULL);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('email_contents');
  }
}
