<?php


namespace Main\Setting\Traits;


use Main\Language\Translates\Translates;

trait SettingTraits
{

    public $env = [];
    private $publicEnv = [
        'APP_NAME', 'APP_URL', 'APP_DESCRIPTION'
    ];

    public function __construct()
    {
        $this->makeEnv();
    }



    public function get($settings = null)
    {

        $result = $this->env;
        if($settings and gettype($settings) === 'array' && !empty($settings)){
            $result = [];

            foreach ($settings as $setting){
                $result[$setting] = ["trans" => Translates::trans($setting) , "value" => ""];

                if(isset($this->env[$setting])){
                    $result[$setting]['value'] = $_ENV[$setting];
                }

            }

        }

        return collect($result);
    }

    public function put($envs)
    {
        foreach ($envs as $keyEnv => $env){
            $this->setEnvironmentValue($keyEnv, $env);
        }
        $this->makeEnv();

        return $this->get();

    }

    public function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $newEnvValue = str_replace('"', '', $envValue);
        if(strpos($str, "{$envKey}={$_ENV[$envKey]}")) {
            $str = str_replace("{$envKey}={$_ENV[$envKey]}", "{$envKey}=\"{$newEnvValue}\"", $str);
        }else{
            $str = str_replace("{$envKey}=\"{$_ENV[$envKey]}\"", "{$envKey}=\"{$newEnvValue}\"", $str);
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        $_ENV[$envKey] = $newEnvValue;
    }


    private function makeEnv()
    {

        if(auth('api')->user() && auth('api')->user()->can(__CLASS__)){
          return  $this->env = collect($_ENV)->map(function ($item, $key){
              return ["trans" => Translates::trans($key), "value" => $item];
          })->toArray();
        }

        foreach ($this->publicEnv as $publicEnv){
            $this->env[$publicEnv] = ["trans" => Translates::trans($publicEnv), "value" => $_ENV[$publicEnv]];
        }

    }

}
