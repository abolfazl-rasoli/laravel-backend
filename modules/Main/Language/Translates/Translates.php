<?php


namespace Main\Language\Translates;


use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Main\Language\Traits\TranslateTraits;

class Translates
{

    use TranslateTraits;


    public static function transByLocal($local, $getAll = true)
    {
        $result = self::getMainLocal($local);
        if ($getAll) $result = array_merge($result, self::getJsonWithVariable($local));
        return $result;
    }

    public static function updateJsonFile($oldLocal, $newLocal)
    {

        $storage = Storage::disk('translate');
        $filename = $newLocal . '.json';
        $oldFilename = $oldLocal . '.json';
        $_filename = '_' . $newLocal . '.json';
        $_oldFilename = '_' . $oldLocal . '.json';

        self::transByLocal($newLocal);
        if($storage->exists($oldFilename)){
            $storage->put($filename, $storage->get($oldFilename), 'public');
        }
        if($storage->exists($_oldFilename)){
            $storage->put($_filename, $storage->get($_oldFilename), 'public');
        }
    }

    public static function trans($text, $lang = null)
    {
        $language = $lang ?? app()->getLocale();
        if (Lang::has('messages.' . $text))
            return trans('messages.' . $text, [], $language);

        return $text;
    }

}
