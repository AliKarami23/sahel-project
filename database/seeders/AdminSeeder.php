<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Admin = User::create([
            'id' => 1,
            'Role' => 'Admin',
            'Full_Name' => 'AliK',
            'PhoneNumber' => '0912',
            'Email' => 'ali@gmail.com',
            'Password' => Hash::make('00000000')
        ]);
        $Admin->assignRole('SuperAdmin');
    }
}
