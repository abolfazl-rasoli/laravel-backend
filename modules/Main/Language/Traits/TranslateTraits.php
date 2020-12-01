<?php


namespace Main\Language\Traits;


use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

Trait TranslateTraits
{

    public static function getMainLocal($local)
    {
        $fileName = $local . '.json';
        return self::getByFileName($fileName, self::getAnyJsonFile());
    }

    private static function getJsonWithVariable($local)
    {
        $fileName = '_' . $local . '.json';
        return self::getByFileName($fileName, self::_getAnyJsonFile());
    }

    private static function getByFileName($fileName, $newJsonFile){
        $storage = Storage::disk('translate');

        if (!$storage->exists($fileName)) {
            $storage->put($fileName, $newJsonFile, 'public');
        }
        return json_decode($storage->get($fileName), true);
    }


    public static function getAnyJsonFile()
    {

        $storage = Storage::disk('translate');

        foreach ($storage->allFiles() as $file) {
            if ($file[0] !== '_') {
                return $storage->get($file);
            }
        }
        return '{}';
    }

    public static function _getAnyJsonFile()
    {

        $storage = Storage::disk('translate');

        foreach ($storage->allFiles() as $file) {
            if ($file[0] === '_') {
                return $storage->get($file);
            }
        }
        return '{}';
    }


}
