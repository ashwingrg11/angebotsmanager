<?php

use Illuminate\Database\Seeder;

class OfferStatusTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table_data = array(
      ['label' => 'first offer draft received'],
      ['label' => 'renegotiation in progress'],
      ['label' => 'final offer received'],
      ['label' => 'final offer cross checked'],
      ['label' => 'style approved'],
      ['label' => 'images received'],
      ['label' => 'sent for translation'],
      ['label' => 'translation received'],
      ['label' => 'final check'],
      ['label' => 'additional text information received']
    );
    DB::table('offer_status')->insert($table_data);
  }
}
