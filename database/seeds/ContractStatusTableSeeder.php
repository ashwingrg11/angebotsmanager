<?php

use Illuminate\Database\Seeder;

class ContractStatusTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table_data = array(
      ['label' => 'cooperation agreement sent'],
      ['label' => 'cooperation agreement received'],
      ['label' => 'final clearance agreement sent'],
      ['label' => 'final clearance agreement received'],
      ['label' => 'Not Applicable']
    );
    DB::table('contract_status')->insert($table_data);
  }
}
