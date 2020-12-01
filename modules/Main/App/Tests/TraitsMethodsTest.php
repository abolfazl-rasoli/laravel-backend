<?php


namespace Main\App\Tests;


use Main\User\Model\User;

trait TraitsMethodsTest
{

    private $assertCallBackJson = ['data', 'message' , 'error', 'success'];


    public function jsonCallback(Array $newCallback = [])
    {
        if(count($newCallback) === 0) return $this->assertCallBackJson;

        $result = $this->assertCallBackJson;
        foreach ($newCallback as $key => $value){

            if(gettype( array_search($key , $result)) === 'integer'){

                unset($result[array_search($key , $result)]);
                $result[$key] = $value;
            };

        }

        return $result;
    }

    public function fillable($model,Array $hidden = [])
    {
        return array_diff(resolve($model)->getFillable(), array_merge(resolve($model)->getHidden(), $hidden));
    }
}
