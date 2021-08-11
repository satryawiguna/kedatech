<?php
namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('users')->truncate();
        DB::table('user_types')->truncate();

        $this->call(UserTypeSeeder::class);
        $this->call(UserSeeder::class);
    }
}
