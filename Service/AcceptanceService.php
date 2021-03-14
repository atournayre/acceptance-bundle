<?php

namespace Atournayre\AcceptanceBundle\Service;

use Atournayre\AcceptanceBundle\Exception\DateConversionException;
use Atournayre\AcceptanceBundle\Exception\DateTimeNullException;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AcceptanceService
{
    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @var string
     */
    private $startDateTime;

    /**
     * @var string
     */
    private $endDateTime;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->isEnabled = $parameterBag->get('atournayre_acceptance.is_enabled');
        $this->startDateTime = $parameterBag->get('atournayre_acceptance.start_date_time');
        $this->endDateTime = $parameterBag->get('atournayre_acceptance.end_date_time');
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

    public function isServiceEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @return bool
     * @throws DateConversionException
     * @throws DateTimeNullException
     */
    public function isCurrentlyDisabled(): bool
    {
        $startDateTime = $this->getStartDateTime();
        $endDateTime = $this->getEndDateTime();
        $currentDateTime = new DateTime();

        return $currentDateTime < $startDateTime
            || $endDateTime < $currentDateTime;
    }

    /**
     * @return DateTime
     * @throws DateConversionException
     * @throws DateTimeNullException
     */
    public function getStartDateTime(): DateTime
    {
        if (is_null($this->startDateTime)) {
            throw new DateTimeNullException('Start date time cannot be null, please provide start datetime.');
        }
        return $this->convertDateTime($this->startDateTime);
    }

    /**
     * @return DateTime
     * @throws DateConversionException
     * @throws DateTimeNullException
     */
    public function getEndDateTime(): DateTime
    {
        if (is_null($this->endDateTime)) {
            throw new DateTimeNullException('End date time cannot be null, please provide end datetime.');
        }
        return $this->convertDateTime($this->startDateTime);
    }
}