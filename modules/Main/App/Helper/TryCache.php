<?php

namespace Main\App\Helper;

use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Main\User\Exceptions\LoginException;

class TryCache
{
    private static function log($e)
    {
        Log::error($e);
        if ($e instanceof LoginException) {
            return new MessagesResource(['s' => $e->getCode(), 'message' => $e->getMessage()]);
        }
        dd($e);
        throw new Exception("error server", 500);
    }

    public static function render($tryFunc, $DBRollback = null)
    {
        try {
            return $tryFunc();
        } catch (Exception $exception) {
            $DBRollback && DB::rollBack();
            return self::log($exception);
        }
    }
}
