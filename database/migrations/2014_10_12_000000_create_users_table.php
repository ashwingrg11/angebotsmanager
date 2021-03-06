<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('first_name', 45);
      $table->string('last_name', 45);
      $table->string('telephone')->nullable()->default(null);
      $table->string('username');
      $table->string('email')->unique();
      $table->string('password', 60);
      $table->enum('user_type', ['admin', 'general', 'viewer', 'external_partner'])->default('admin');
      $table->rememberToken();
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
    Schema::drop('users');
  }
}
