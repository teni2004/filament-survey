<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Survey;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([TeamSeeder::class]);
        User::factory(10)->create();
        Survey::factory(5)->create();
        $this->call([AdminSeeder::class]);
    }
}
