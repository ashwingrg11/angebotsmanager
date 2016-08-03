<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateOffersTable extends Migration
{
  /**
   * Run the migrations.
   * create offers table
   * @return void
   */
  public function up()
  {
    Schema::create('offers', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('project_id')->unsigned();
      $table->integer('partner_id')->unsigned();
      $table->integer('contact_id')->unsigned();
      $table->integer('ji_contact_id')->unsigned();
      $table->integer('offer_type_category_masterlist_id')->unsigned()->nullable()->default(NULL);
      $table->integer('general_offer_type_masterlist_id')->unsigned()->nullable()->default(NULL);
      $table->integer('detailed_offer_type_masterlist_id')->unsigned()->nullable()->default(NULL);
      $table->integer('country_id')->unsigned()->nullable()->default(NULL);
      $table->integer('contract_status_id')->unsigned()->nullable()->default(NULL);
      // $table->string('title');
      $table->string('title_en')->nullable()->default(NULL);
      $table->string('title_de')->nullable()->default(NULL);
      $table->text('location')->nullable()->default(NULL);
      $table->date('valid_from')->default(Carbon::now()->addDays(2)->format('Y-m-d'));
      $table->date('valid_until')->default(Carbon::now()->addDays(5)->format('Y-m-d'));
      $table->string('street1')->nullable()->default(NULL);
      $table->string('street2')->nullable()->default(NULL);
      $table->string('street3')->nullable()->default(NULL);
      $table->string('postal_code', 45)->nullable()->default(NULL);
      $table->string('city', 45)->nullable()->default(NULL);
      $table->string('county', 45)->nullable()->default(NULL);
      $table->string('state', 45)->nullable()->default(NULL);
      $table->string('market', 90)->nullable()->default(NULL);
      $table->text('price_en')->nullable()->default(NULL);
      $table->text('price_de')->nullable()->default(NULL);
      $table->text('offer_en')->nullable()->default(NULL);
      $table->text('offer_de')->nullable()->default(NULL);
      $table->text('benefit_en')->nullable()->default(NULL);
      $table->text('benefit_de')->nullable()->default(NULL);
      $table->text('further_information_en')->nullable()->default(NULL);
      $table->text('further_information_de')->nullable()->default(NULL);
      $table->string('reservation_telephone', 45)->nullable()->default(NULL);
      $table->string('reservation_fax', 45)->nullable()->default(NULL);
      $table->string('reservation_email', 100)->nullable()->default(NULL);
      $table->string('reservation_url')->nullable()->default(NULL);
      $table->text('how_to_book_en')->nullable()->default(NULL);
      $table->text('how_to_book_de')->nullable()->default(NULL);
      $table->text('exclusive_advantage')->nullable()->default(NULL);
      $table->text('note')->nullable()->default(NULL);
      $table->text('editorial_note')->nullable()->default(NULL);
      $table->enum('has_communication_package', ['yes', 'no'])->default('yes');
      $table->string('offer_code', 45);
      $table->boolean('activation_flag')->default(0);
      $table->enum('activation_email', ['n-a', 'pending', 'sent'])->default('n-a');
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('project_id')
          ->references('id')
          ->on('projects')
          ->onUpdate('cascade');
      $table->foreign('partner_id')
          ->references('id')
          ->on('partners')
          ->onUpdate('cascade');
      $table->foreign('contact_id')
          ->references('id')
          ->on('contacts')
          ->onUpdate('cascade');
      $table->foreign('ji_contact_id')
          ->references('id')
          ->on('users')
          ->onUpdate('cascade');
      $table->foreign('offer_type_category_masterlist_id')
          ->references('id')
          ->on('offer_type_category_masterlist')
          ->onUpdate('cascade');
      $table->foreign('general_offer_type_masterlist_id')
          ->references('id')
          ->on('general_offer_type_masterlist')
          ->onUpdate('cascade');
      $table->foreign('detailed_offer_type_masterlist_id')
          ->references('id')
          ->on('detailed_offer_type_masterlist')
          ->onUpdate('cascade');
      $table->foreign('contract_status_id')
          ->references('id')
          ->on('contract_status')
          ->onUpdate('cascade');
      $table->foreign('country_id')
          ->references('id')
          ->on('countries')
          ->onUpdate('cascade');
    });
    /*************  pivot table offer_placement **************/
    Schema::create('offer_placement', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('offer_id')->unsigned()->index();
      $table->integer('placement_id')->unsigned()->index();
      $table->date('placement_start_date')->default(Carbon::now()->format('Y-m-d'));
      $table->date('placement_end_date')->default(Carbon::now()->addDays(5)->format('Y-m-d'));
      $table->timestamps();
      $table->foreign('offer_id')
          ->references('id')
          ->on('offers')
          ->onDelete('cascade');
      $table->foreign('placement_id')
          ->references('id')
          ->on('placements')
          ->onDelete('cascade');
    });
    /*************  pivot table offer_offer_status **************/
    Schema::create('offer_offer_status', function (Blueprint $table) {
      $table->integer('offer_id')->unsigned()->index();
      $table->integer('offer_status_id')->unsigned()->index();
      $table->enum('type', ['yes', 'na'])->default('yes');
      $table->timestamps();
      $table->foreign('offer_id')
          ->references('id')
          ->on('offers')
          ->onDelete('cascade');
      $table->foreign('offer_status_id')
          ->references('id')
          ->on('offer_status')
          ->onDelete('cascade');
    });
    /*************  pivot table offer_user **************/
    Schema::create('offer_user', function (Blueprint $table) {
      $table->integer('offer_id')->unsigned()->index();
      $table->integer('user_id')->unsigned()->index();
      $table->timestamps();
      $table->foreign('offer_id')
          ->references('id')
          ->on('offers')
          ->onDelete('cascade');
      $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   * drop offers table
   * @return void
   */
  public function down()
  {
    Schema::drop('offer_user');
    Schema::drop('offer_offer_status');
    Schema::drop('offer_placement');
    // Schema::drop('offer_placement');
    Schema::drop('offers');
  }
}
