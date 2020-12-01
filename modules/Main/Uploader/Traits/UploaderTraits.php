<?php


namespace Main\Uploader\Traits;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Main\Uploader\Uploader;

trait UploaderTraits
{

    public function get()
    {

        $avatar = Uploader::getUrlAllFileADisk('avatar');
        $image = Uploader::getUrlAllFileADisk('image');
        $video = Uploader::getUrlAllFileADisk('video');
        $document = Uploader::getUrlAllFileADisk('document');

        return [
            'avatar' => $avatar
            , 'image' => $image
            , 'video' => $video
            , 'document' => $document
        ];

    }

    public function uploadFiles($files)
    {
        $mimes = collect(Uploader::$mimes);
        foreach ($files as $file){
            $disk = $mimes->search(function ($item) use ($file) {
                return gettype(collect($item)->search($file->getClientOriginalExtension())) === 'integer';
            });
            Uploader::upload('/' , $file , $disk);
        }
        return $this->get();
    }

    public function deleteFiles($urls)
    {
        $diskName = 'uploader';
        $disk = Storage::disk('uploader');
        collect($urls)->each(function ($url) use ($diskName, $disk) {

           $nameFile = Str::after($url , 'uploader/');
           if($disk->exists($nameFile)){
               Uploader::removeFile($nameFile, $diskName);
           }

        });

        return $this->get();
    }

}
