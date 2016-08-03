<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
  /**
   * Run the migrations.
   * create partners table
   * @return void
   */
  public function up()
  {
    Schema::create('partners', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('address_id')->unsigned();
      // $table->integer('project_id')->unsigned();
      $table->string('partner_name', 45);
      $table->text('notes')->nullable()->default(NULL);
      $table->string('region', 45)->nullable()->default(NULL);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('address_id')
          ->references('id')
          ->on('address')
          ->onUpdate('cascade');
      /*$table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onUpdate('cascade');*/
    });
    /*************  pivot table contact_partner **************/
    Schema::create('contact_partner', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('contact_id')->unsigned()->index();
      $table->integer('partner_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('contact_id')
          ->references('id')
          ->on('contacts')
          ->onDelete('cascade');
      $table->foreign('partner_id')
          ->references('id')
          ->on('partners')
          ->onDelete('cascade');
    });
    /*************  pivot table partner_project **************/
    Schema::create('partner_project', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('partner_id')->unsigned()->index();
      $table->integer('project_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('partner_id')
          ->references('id')
          ->on('partners')
          ->onDelete('cascade');
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop partners table
   * @return void
   */
  public function down()
  {
    Schema::drop('partner_project');
    Schema::drop('contact_partner');
    Schema::drop('partners');
  }
}
