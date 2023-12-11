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
            'full_name' => 'AliK',
            'phone_number' => '0912',
            'email' => 'ali@gmail.com',
            'password' => Hash::make('00000000')
        ]);
        $Admin->assignRole('Admin');
    }
}
