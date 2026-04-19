<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un compte administrateur par défaut
        User::updateOrCreate(
            ['email' => 'admin@mentorlink.com'],
            [
                'name' => 'Administrateur',
                'email' => 'admin@mentorlink.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer quelques comptes de test
        User::updateOrCreate(
            ['email' => 'mentor@test.com'],
            [
                'name' => 'Mentor Test',
                'email' => 'mentor@test.com',
                'password' => Hash::make('password'),
                'role' => 'mentor',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'mentee@test.com'],
            [
                'name' => 'Mentee Test',
                'email' => 'mentee@test.com',
                'password' => Hash::make('password'),
                'role' => 'mentee',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Comptes créés :');
        $this->command->info('Admin: admin@mentorlink.com / admin123');
        $this->command->info('Mentor: mentor@test.com / password');
        $this->command->info('Mentee: mentee@test.com / password');
    }
}