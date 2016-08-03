<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table_data = array(
      ['label' => 'English'],
      ['label' => 'German']
    );
    DB::table('languages')->insert($table_data);
  }
}
