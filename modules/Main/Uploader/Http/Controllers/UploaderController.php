<?php

namespace Main\Uploader\Http\Controllers;

use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\Uploader\Http\Requests\DeleteUploaderRequest;
use Main\Uploader\Http\Requests\UploadUploaderRequest;
use Main\Uploader\Http\Requests\ViewUploaderRequest;
use Main\Uploader\Http\Resources\UploaderResource;
use Main\Uploader\Traits\UploaderTraits;

class UploaderController extends Controller
{

    use UploaderTraits;
    /**
     * view all upload file
     * @param ViewUploaderRequest $request
     * @return mixed
     */
    public function view(ViewUploaderRequest $request)
    {
       return TryCache::render(function () use ($request) {

          return new UploaderResource($this->get());

       });
    }

    /**
     * upload multi file
     * @param UploadUploaderRequest $request
     * @return mixed
     */
    public function upload(UploadUploaderRequest $request)
    {
       return TryCache::render(function () use ($request) {

          return new UploaderResource($this->uploadFiles($request->file('files')));

       });
    }

    /**
     * delete multi file
     * @param DeleteUploaderRequest $request
     * @return mixed
     */
    public function delete(DeleteUploaderRequest $request)
    {
       return TryCache::render(function () use ($request) {

          return new UploaderResource($this->deleteFiles($request->urls));

       });
    }

}
