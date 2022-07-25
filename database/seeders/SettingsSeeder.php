<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'app_name' => 'TIGER APP',
            'logo' => 'default.png',
            'email' => 'farestarikhassan7@gmail.com',
            'phone' => '+201018730620',
            'address' => 'Work!',
            'developer' => 'TIGER'
        ]);
    }
}
