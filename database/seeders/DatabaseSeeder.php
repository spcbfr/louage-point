<?php

namespace Database\Seeders;

use App\Models\Station;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (App::isLocal()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
            ]);
            Station::factory(15)->create();
        }

    }
}
