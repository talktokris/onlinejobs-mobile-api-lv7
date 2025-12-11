<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin user already exists
        $existingUser = User::where('email', 'freshkris@gmail.com')->first();
        
        if ($existingUser) {
            // Update existing user
            $existingUser->password = Hash::make('Krishna123');
            $existingUser->role_id = 3;
            $existingUser->status = 1;
            $existingUser->name = 'Admin';
            $existingUser->save();
            $this->command->info('Admin user updated successfully!');
        } else {
            // Create new admin user
            $user = new User();
            $user->name = 'Admin';
            $user->email = 'freshkris@gmail.com';
            $user->password = Hash::make('Krishna123');
            $user->role_id = 3; // Admin role
            $user->status = 1; // Active
            $user->public_id = time() . md5('freshkris@gmail.com');
            $user->is_email_verified = 1;
            $user->save();
            $this->command->info('Admin user created successfully!');
        }
    }
}

