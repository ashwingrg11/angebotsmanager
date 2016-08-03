<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferReportsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('offer_reports', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('offer_id')->unsigned();
      $table->integer('for_year')->default(\Carbon\Carbon::now()->format('Y'));
      $table->string('for_month', 10)->default(\Carbon\Carbon::now()->format('M'));
      $table->integer('no_of_requests')->nullable()->default(NULL);
      $table->integer('no_of_final_bookings')->nullable()->default(NULL);
      $table->integer('no_of_bookings_another_offering')->nullable()->default(NULL);
      $table->integer('no_of_packages_sold')->nullable()->default(NULL);
      $table->enum('report_type', ['microsite1', 'microsite2'])->default('microsite1');
      $table->enum('filled_in', ['yes', 'no'])->default('no');
      $table->date('report_date')->nullable()->default(\Carbon\Carbon::now());
      $table->string('report_code', 10);
      $table->text('comments')->nullable()->default(NULL);
      $table->enum('extension_request', ['yes', 'no', 'n-a'])->default('n-a');
      $table->timestamps();
      /*************  foreign keys **************/
      $table->foreign('offer_id')
          ->references('id')
          ->on('offers')
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
    Schema::drop('offer_reports');
  }
}
