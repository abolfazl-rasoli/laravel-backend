<?php


namespace Main\Language\Traits;


use Illuminate\Support\Facades\DB;
use Main\App\Helper\MessagesResource;
use Main\Language\Http\Resources\LanguageResource;
use Main\Language\Model\Language;

trait LanguageTrait
{

    private function languageView()
    {
        return Language::all();
    }


    private function languageCreate($value)
    {
        DB::beginTransaction();
        $newLang =  Language::create($value)->fresh();
        DB::commit();

        return $newLang;
    }

    private function languageEdit($lang, $value)
    {
        DB::beginTransaction();
        $lang->update($value);
        $updateLang =  $lang->fresh();
        DB::commit();

        return $updateLang;
    }

    private function languageDelete($lang)
    {
        if($lang->primary === 1){
            return new MessagesResource(
                ['s' => 400, 'message' => trans('messages.dont_remove_primary_lang') ]);
        }
        $lang->forceDelete();
        return new LanguageResource(Language::all());
    }

    private function languagePrimary($lang)
    {
        Language::where('primary', 1)->update(['primary' => 0]);

        $lang->update(['primary' => true]);

        return $lang->fresh();
    }

}
