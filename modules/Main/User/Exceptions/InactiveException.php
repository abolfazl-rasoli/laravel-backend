<?php

namespace Main\User\Exceptions;

use Exception;
use Main\App\Helper\MessagesResource;

class InactiveException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return new MessagesResource(['message' => $this->getMessage(), 's' => 401]);
    }
}
