<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // DB::table('users')->insert([
        //     'name' => 'Timothy Arella',
        //     'email' => 'timothyarella7@gmail.com',
        //     'password' => '$2y$12$FiW.ugVcB4vjIObRXtIWveXj4x2a8iNZe7n8T8fGy9ISPsp/ZegvG',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        DB::table('school')->insert([
            [
                'name' => 'TK Bina Bakti 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SD Bina Bakti 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SMP Bina Bakti 3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SD Talenta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
