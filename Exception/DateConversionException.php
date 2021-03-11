<?php

namespace Atournayre\AcceptanceBundle\Exception;

use Throwable;

class DateConversionException extends \Exception
{
    const DEFAULT_MESSAGE = 'Oops, an error occurs during conversion of "%s" into date time.';

    public function __construct(string $datetime, ?string $message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = sprintf($message ?? self::DEFAULT_MESSAGE, $datetime);
    }
}