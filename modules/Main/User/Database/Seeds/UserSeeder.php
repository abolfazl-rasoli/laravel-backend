<?php
namespace Main\User\Database\Seeds;

use Illuminate\Database\Seeder;
use Main\User\Model\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->superAdmin();
        $this->user();
    }

    public function user(): void
    {
        factory(User::class, 9)->create();
    }

    public function superAdmin(): void
    {
        factory(User::class)->create(['role' => 1, 'email' => '925a1994@gmail.com']);
    }

}
