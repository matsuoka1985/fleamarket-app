<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        User::all()->each(function ($user) {
            Address::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
