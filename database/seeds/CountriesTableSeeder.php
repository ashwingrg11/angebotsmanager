<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table_data = array(
      ['label' => 'Germany'],
      ['label' => 'England']
    );
    DB::table('countries')->insert($table_data);
  }
}
