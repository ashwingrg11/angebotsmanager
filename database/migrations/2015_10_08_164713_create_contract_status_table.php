<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractStatusTable extends Migration
{
  /**
   * Run the migrations.
   * create contract_status table
   * @return void
   */
  public function up()
  {
    Schema::create('contract_status', function (Blueprint $table) {
      $table->increments('id');
      $table->string('label', 90);
      // $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   * drop contract_status table
   * @return void
   */
  public function down()
  {
    Schema::drop('contract_status');
  }
}
