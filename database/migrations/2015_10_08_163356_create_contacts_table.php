<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
  /**
   * Run the migrations.
   * create contacts table
   * @return void
   */
  public function up()
  {
    Schema::create('contacts', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('language_id')->unsigned()->nullable()->default(NULL);
      $table->integer('address_id')->unsigned();
      // $table->string('language');
      $table->string('title');
      $table->text('greeting');
      $table->string('first_name')->nullable()->default(NULL);
      $table->string('last_name');
      $table->string('email', 100);
      $table->string('job_title')->nullable()->default(NULL);
      $table->string('department')->nullable()->default(NULL);
      $table->string('company');
      $table->string('phone', 45)->nullable()->default(NULL);
      $table->string('mobile_phone', 45)->nullable()->default(NULL);
      $table->text('notes')->nullable()->default(NULL);
      $table->timestamps();
      $table->foreign('address_id')
          ->references('id')
          ->on('address')
          ->onUpdate('cascade');
      $table->foreign('language_id')
          ->references('id')
          ->on('languages')
          ->onUpdate('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop contacts table
   * @return void
   */
  public function down()
  {
    Schema::drop('contacts');
  }
}
