<?php

use App\Product;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // fake user
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'username' => 'user'.$i,
                'name' => 'User '.$i,
                'password' => Hash::make('password'),
                'email' => 'user'.$i.'@user.com',
            ]);

            for ($j = 1; $j <= 5; $j++) {
                Product::create([
                    'product_name' => 'Produk #'.$j.' Si '.$user->name,
                    'price' => mt_rand(1000, 5000000),
                    'stock' => mt_rand(10, 100),
                    'imageurl' => 'https://www.gravatar.com/avatar/'.md5($user->email).'?d=wavatar',
                    'created_by' => $user->id
                ]);
            }
        }
    }
}
