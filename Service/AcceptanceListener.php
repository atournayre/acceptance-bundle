<?php

namespace Atournayre\AcceptanceBundle\Service;

use Atournayre\AcceptanceBundle\Exception\DateConversionException;
use Atournayre\AcceptanceBundle\Exception\NoEndDateException;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class AcceptanceListener
{
    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @var DateTime
     */
    private $startDateTime;

    /**
     * @var DateTime
     */
    private $endDateTime;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $templateError;

    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var string
     */
    private $noEndDateMessage;
    /**
     * @var string
     */
    private $dateConversionErrorMessage;

    public function __construct(ParameterBagInterface $parameterBag, Environment $environment)
    {
        if (!$parameterBag->has('atournayre_acceptance.is_enabled')) {
            throw new ParameterNotFoundException('atournayre_acceptance.is_enabled');
        }

        if (!$parameterBag->has('atournayre_acceptance.start_date_time')) {
            throw new ParameterNotFoundException('atournayre_acceptance.start_date_time');
        }

        if (!$parameterBag->has('atournayre_acceptance.end_date_time')) {
            throw new ParameterNotFoundException('atournayre_acceptance.end_date_time');
        }

        if (!$parameterBag->has('atournayre_acceptance.template')) {
            throw new ParameterNotFoundException('atournayre_acceptance.template');
        }

        if (!$parameterBag->has('atournayre_acceptance.templateError')) {
            throw new ParameterNotFoundException('atournayre_acceptance.templateError');
        }

        if (!$parameterBag->has('atournayre_acceptance.noEndDateMessage')) {
            throw new ParameterNotFoundException('atournayre_acceptance.noEndDateMessage');
        }

        if (!$parameterBag->has('atournayre_acceptance.dateConversionErrorMessage')) {
            throw new ParameterNotFoundException('atournayre_acceptance.dateConversionErrorMessage');
        }

        $this->isEnabled = $parameterBag->get('atournayre_acceptance.is_enabled');
        $this->startDateTime = $parameterBag->get('atournayre_acceptance.start_date_time');
        $this->endDateTime = $parameterBag->get('atournayre_acceptance.end_date_time');
        $this->template = $parameterBag->get('atournayre_acceptance.template');
        $this->templateError = $parameterBag->get('atournayre_acceptance.templateError');
        $this->noEndDateMessage = $parameterBag->get('atournayre_acceptance.noEndDateMessage');
        $this->dateConversionErrorMessage = $parameterBag->get('atournayre_acceptance.dateConversionErrorMessage');
        $this->environment = $environment;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        try {
            $startDateTime = $this->convertDateTime($this->startDateTime);

            if (!$this->isEnabled) {
                $this->setDisabledResponse($event, $startDateTime);
            }

            if (is_null($this->endDateTime)) {
                throw new NoEndDateException($this->noEndDateMessage);
            }

            if ($this->acceptanceIsDisableForToday($startDateTime, $this->convertDateTime($this->endDateTime))) {
                $this->setDisabledResponse($event, $startDateTime);
            }
        } catch (DateConversionException | NoEndDateException $exception) {
            $this->setErrorResponse($event, $exception->getMessage());
        } catch (Exception $exception) {
            $this->setErrorResponse($event, 'Oops, an error occurs in acceptance.');
        }
    }

    /**
     * @param string $dateTime
     * @return DateTime
     * @throws DateConversionException
     */
    private function convertDateTime(string $dateTime): DateTime
    {
        try {
            return new DateTime($dateTime);
        } catch (Exception $exception) {
            throw new DateConversionException($dateTime, $this->dateConversionErrorMessage);
        }
    }

    private function setDisabledResponse(RequestEvent $event, ?DateTime $startDateTime = null): void
    {
        $this->setResponse(
            $this->template,
            [
                'start_date_time' => $startDateTime,
            ],
            $event
        );
    }

    private function acceptanceIsDisableForToday(DateTime $startDateTime, DateTime $endDateTime): bool
    {
        $currentDateTime = new DateTime();

        return $currentDateTime < $startDateTime
            || $endDateTime < $currentDateTime;
    }

    private function setErrorResponse(RequestEvent $event, string $message): void
    {
        $this->setResponse(
            $this->templateError,
            [
                'message' => $message,
            ],
            $event
        );
    }

    private function setResponse(string $template, array $options, RequestEvent $event)
    {
        $template = $this->environment->render($template, $options);
        $response = new Response($template);
        $event->setResponse($response);
    }
}
