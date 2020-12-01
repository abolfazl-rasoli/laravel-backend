<?php

use Illuminate\Database\Seeder;
use Main\User\Database\Seeds\PassportSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PassportSeeder::class);
        $this->call(\Main\Language\Database\Seeds\LanguageSeeder::class);
        $this->call(\Main\Role\Database\Seeds\RoleSeeder::class);
        $this->call(\Main\Permission\Database\Seeds\PermissionSeeder::class);
        $this->call(\Main\User\Database\Seeds\UserSeeder::class);
        $this->call(\Main\Notification\Database\Seeds\NotificationTypesSeeder::class);
        $this->call(\Main\Notification\Database\Seeds\NotificationSeeder::class);
    }
}
