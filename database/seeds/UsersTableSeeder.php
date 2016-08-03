<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table_data = array(
      'first_name' => 'John',
      'last_name' => 'Doe',
      'telephone' => '9808288010',
      'username' => 'johndoe',
      'email' => 'admin@admin.com',
      'password' => bcrypt('password'),
      'user_type' => 'admin'
    );
    DB::table('users')->insert($table_data);
  }
}
