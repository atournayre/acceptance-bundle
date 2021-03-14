<?php

namespace Atournayre\AcceptanceBundle\Service;

use Atournayre\AcceptanceBundle\Exception\DateConversionException;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AcceptanceService
{
    /**
     * @var DateTime
     */
    private $endDateTime;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->endDateTime = $parameterBag->get('atournayre_acceptance.end_date_time');
    }

    /**
     * @return DateTime
     * @throws DateConversionException
     */
    public function getEndDateTime(): DateTime
    {
        return $this->convertDateTime($this->endDateTime);
    }

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