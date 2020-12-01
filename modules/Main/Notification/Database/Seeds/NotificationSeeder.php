<?php
namespace Main\Notification\Database\Seeds;

use Illuminate\Database\Seeder;
use Main\Notification\Model\Notification;
use Main\Role\Model\Role;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createNotification();

    }

    public function createNotification(): void
    {
        Notification::create([
           'user_id' => 1,
           'type' => 3,
           'title' => 'کد تخفیف'
        ]);
        Notification::create([
           'user_id' => 1 ,
           'type' => 4,
           'title' => 'برنامه جدید',
            'link' => env('APP_URL')
        ]);

        Notification::create([
           'user_id' => 1 ,
           'type' => 1,
           'title' => 'برنامه جدید2',
        ]);
    }


}
