<?php

namespace Atournayre\AcceptanceBundle\Service;

use Atournayre\AcceptanceBundle\Exception\DateConversionException;
use DateTime;
use Exception;

final class AcceptanceService
{
    /**
     * @param string $dateTime
     * @return DateTime
     * @throws DateConversionException
     */
    public function convertDateTime(string $dateTime): DateTime
    {
        try {
            return new DateTime($dateTime);
        } catch (Exception $exception) {
            throw new DateConversionException($dateTime);
        }
    }

    public function isDisabledForToday(DateTime $startDateTime, DateTime $endDateTime): bool
    {
        $currentDateTime = new DateTime();

        return $currentDateTime < $startDateTime
            || $endDateTime < $currentDateTime;
    }
}