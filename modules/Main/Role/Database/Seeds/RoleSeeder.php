<?php
namespace Main\Role\Database\Seeds;

use Illuminate\Database\Seeder;
use Main\Role\Model\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->superAdmin();

        $this->admin();
    }

    public function superAdmin(): void
    {
        Role::create(['title' => 'super_admin'])->delete();
    }

    public function admin(): void
    {
        Role::create(['title' => 'admin']);
    }

}
