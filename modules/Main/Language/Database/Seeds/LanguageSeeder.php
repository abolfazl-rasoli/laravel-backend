<?php
namespace Main\Language\Database\Seeds;

use Illuminate\Database\Seeder;
use Main\Language\Model\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->lang();
    }

    public function lang(): void
    {
        Language::create(['lang' => 'en']);
        Language::create(['lang' => 'fa', 'primary' => 1]);
        Language::create(['lang' => 'sp']);
    }

}
