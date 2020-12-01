<?php


namespace Main\Uploader;


use Illuminate\Support\Facades\Storage;

class Uploader
{

    public static $mimes = [
        'image' => ['png', 'jpeg', 'jpg','svg' , 'gif'],
        'video' => ['mp4'],
        'document' => ['pdf'  , 'mp3' , 'doc' , 'docx']
    ];

    public static function upload($path, $file, $diskName)
    {
        $disk = Storage::disk($diskName);
        return $disk->put($path, $file);

    }

    public static function getUrl($path, $diskName)
    {
        $disk = Storage::disk($diskName);
        return $disk->url($path);
    }

    public static function getUrlAllFileADisk($diskName)
    {
        $disk = Storage::disk($diskName);
        return collect($disk->allFiles())->map(function ($item) use ($disk) {
            return $disk->url($item);
        })->toArray();
    }

    public static function removeFile($path, $diskName)
    {
        $disk = Storage::disk($diskName);
        return $disk->delete($path);

    }



}
