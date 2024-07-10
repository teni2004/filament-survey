<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Team;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //This is a user that is part of all teams
        $user = User::create([
            'name' => 'SuperAdmin',
            'email' => 'admin@givelify.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
        ]);

        $allteams = Team::all();

        foreach($allteams as $team)
        {
            $user->teams()->attach($team->id);
        }
    }
}
