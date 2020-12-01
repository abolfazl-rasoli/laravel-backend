<?php


namespace Main\Language\Http\Observe;


use Illuminate\Support\Facades\Storage;
use Main\Language\Model\Language;
use Main\Language\Translates\Translates;

class LanguageObserve
{

    public function created(Language $language)
    {
        $storage = Storage::disk('langResource');

        $filename = $language->lang . '/messages.php';

        Translates::transByLocal($language->lang);
        if (!$storage->exists($filename)) {
            $class = Translates::class;
            $put = "<?php \n\nreturn $class::transByLocal('" . $language->lang . "');";
            $storage->put($filename, $put, 'public');
        }

    }

    public function updated(Language $language)
    {
        $old = $language->getOriginal('lang');
        $new = $language->lang;

        $storage = Storage::disk('langResource');

        $filename = $language->lang . '/messages.php';

        Translates::transByLocal($language->lang);

        $class = Translates::class;
        $put = "<?php \n\nreturn $class::transByLocal('" . $language->lang . "');";
        $storage->put($filename, $put, 'public');

        Translates::updateJsonFile($old, $new);
    }

}
