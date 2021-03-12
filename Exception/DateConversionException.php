<?php

namespace Atournayre\AcceptanceBundle\Exception;

use Throwable;

class DateConversionException extends \Exception
{
    public function __construct(string $datetime, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = sprintf('Oops, an error occurs during conversion of "%s" into date time.', $datetime);
    }
}