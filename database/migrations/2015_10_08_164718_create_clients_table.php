<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
  /**
   * Run the migrations.
   * create clients table
   * @return void
   */
  public function up()
  {
    Schema::create('clients', function (Blueprint $table) {
      $table->increments('id');
      // $table->integer('contact_id')->unsigned();
      $table->integer('address_id')->unsigned();
      //$table->integer('project_id')->nullable()->unsigned()->default(null);
      $table->string('name');
      $table->text('notes')->nullable()->default(NULL);
      $table->string('region')->nullable()->default(NULL);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('address_id')
          ->references('id')
          ->on('address')
          ->onUpdate('cascade');
    });
    /*************  pivot table client_contact **************/
    Schema::create('client_contact', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('client_id')->unsigned()->index();
      $table->integer('contact_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('client_id')
          ->references('id')
          ->on('clients')
          ->onDelete('cascade');
      $table->foreign('contact_id')
          ->references('id')
          ->on('contacts')
          ->onDelete('cascade');
    });
    /*************  pivot table client_project **************/
    /*Schema::create('client_project', function (Blueprint $table) {
      $table->integer('client_id')->unsigned()->index();
      $table->integer('project_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('client_id')
          ->references('id')
          ->on('clients')
          ->onDelete('cascade');
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
    });*/
  }

  /**
   * Reverse the migrations.
   * drop clients table
   * @return void
   */
  public function down()
  {
    Schema::drop('client_contact');
    // Schema::drop('client_project');
    Schema::drop('clients');
  }
}
