<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
  /**
   * Run the migrations.
   * create projects table
   * @return void
   */
  public function up()
  {
    Schema::create('projects', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('client_id')->unsigned();
      $table->integer('language_id')->unsigned();
      $table->string('name');
      $table->text('description')->nullable()->default(NULL);
      $table->string('logo')->nullable()->default(NULL);
      $table->date('first_issue_launch_date')->nullable()->default(NULL);
      $table->string('circulation')->nullable()->default(NULL);
      $table->text('note')->nullable()->default(NULL);
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('client_id')
          ->references('id')
          ->on('clients')
          ->onUpdate('cascade');
      $table->foreign('language_id')
          ->references('id')
          ->on('languages')
          ->onUpdate('cascade');
    });
    /*************  pivot table country_project **************/
    /*Schema::create('country_project', function (Blueprint $table) {
      $table->integer('country_id')->unsigned()->index();
      $table->integer('project_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
      $table->foreign('country_id')
          ->references('id')
          ->on('countries')
          ->onDelete('cascade');
    });*/
    /*************  pivot table language_project **************/
    /*Schema::create('language_project', function (Blueprint $table) {
      $table->integer('project_id')->unsigned()->index();
      $table->integer('language_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
      $table->foreign('language_id')
          ->references('id')
          ->on('languages')
          ->onDelete('cascade');
    });*/
    /*************  pivot table project_user **************/
    Schema::create('project_user', function (Blueprint $table) {
      $table->integer('project_id')->unsigned()->index();
      $table->integer('user_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
      $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');
    });
    /*************  pivot table contact_project **************/
    Schema::create('contact_project', function (Blueprint $table) {
      $table->integer('project_id')->unsigned()->index();
      $table->integer('contact_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
      $table->foreign('contact_id')
          ->references('id')
          ->on('contacts')
          ->onDelete('cascade');
    });
    /*************  pivot table category_project **************/
    Schema::create('category_project', function (Blueprint $table) {
      $table->integer('project_id')->unsigned()->index();
      $table->integer('category_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onDelete('cascade');
      $table->foreign('category_id')
          ->references('id')
          ->on('categories')
          ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop projects table
   * @return void
   */
  public function down()
  {
    Schema::drop('category_project');
    Schema::drop('contact_project');
    Schema::drop('project_user');
    // Schema::drop('language_project');
    Schema::drop('projects');
  }
}
