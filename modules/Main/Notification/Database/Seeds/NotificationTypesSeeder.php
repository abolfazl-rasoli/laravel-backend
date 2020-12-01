<?php
namespace Main\Notification\Database\Seeds;

use Illuminate\Database\Seeder;
use Main\Notification\Model\NotificationTypes;
use Main\Role\Model\Role;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createType();

    }

    public function createType(): void
    {
        foreach (NotificationTypes::TYPES as $type){
            NotificationTypes::create([ 'key' => $type]);
        }
    }

}
