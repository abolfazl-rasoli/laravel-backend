<?php

namespace Main\App\Exceptions;

use Exception;
use Main\App\Helper\MessagesResource;
use Throwable;

class ValidateHandler extends Exception
{
    private $m = null;
    private $statusCode;

    /**
     * ValidateHandler constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->m = $message;
        $this->statusCode = $code;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {

    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return new MessagesResource(['s' => $this->statusCode, 'message' => $this->m]);
    }
}
