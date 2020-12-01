<?php

namespace Main\Language\Http\Controllers;
use App\Http\Controllers\Controller;
use Main\App\Helper\TryCache;
use Main\Language\Http\Requests\CreateLanguageRequest;
use Main\Language\Http\Requests\DeleteLanguageRequest;
use Main\Language\Http\Requests\EditLanguageRequest;
use Main\Language\Http\Requests\PrimaryLanguageRequest;
use Main\Language\Http\Requests\ViewLanguageRequest;
use Main\Language\Http\Resources\LanguageResource;
use Main\Language\Traits\LanguageTrait;


class LanguageController extends Controller
{

    use LanguageTrait;

    public function view(ViewLanguageRequest $request)
    {

        return TryCache::render(function () use($request){

            return new LanguageResource($this->languageView());

        });

    }

    public function create(CreateLanguageRequest $request){

        return TryCache::render(function () use($request){

            return new LanguageResource($this->languageCreate($request->validated()));

        }, true);

    }

    public function edit(EditLanguageRequest $request){

        return TryCache::render(function () use($request){

            return new LanguageResource($this->languageEdit($request->language, $request->validated()));

        }, true);

    }

    public function delete(DeleteLanguageRequest $request){

        return TryCache::render(function () use($request){

            return $this->languageDelete($request->language);

        }, true);

    }

    public function primary(PrimaryLanguageRequest $request){

        return TryCache::render(function () use($request){

            return new LanguageResource($this->languagePrimary($request->language));

        });

    }
}
