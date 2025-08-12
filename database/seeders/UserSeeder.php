<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('users')) {
            $this->command?->warn('Users table not found. Skipping UserSeeder.');
            return;
        }

        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        // Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password'),
            ]
        );
        if (! $customer->hasRole('customer')) {
            $customer->assignRole('customer');
        }

        $this->command?->info('UserSeeder: admin@gmail.com & customer@gmail.com created (password: password).');
    }
}
