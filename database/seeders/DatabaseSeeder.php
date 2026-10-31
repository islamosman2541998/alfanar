<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@alfanar.com'], [
            'name' => 'Admin',
            'password' => 'Alfanar@2026',
            'is_active' => true,
        ]);

        $this->call([
            SiteTextSeeder::class,
            ContentSeeder::class,
            SettingSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
