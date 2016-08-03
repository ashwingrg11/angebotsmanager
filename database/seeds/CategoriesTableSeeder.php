<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){
    $table_data = array(
      ['label' => 'Sports'],
      ['label' => 'Entertainment']
    );
    DB::table('categories')->insert($table_data);
  }
}
