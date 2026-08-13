<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;        

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'admin')->first();

        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin',
            
            /* Why didn't we 'password' => Hash::make('admin') ??
                --> because Laravel automatically hashes this via the cast :
                        protected function casts(): array
                        {
                            return [
                                'password' => 'hashed',
                            ];
                        }
                ---- ( in app\Models\User.php) ----
            */

            'role_id' => $adminRole->id,
            'status' => 'active',
        ]);
    }
}
