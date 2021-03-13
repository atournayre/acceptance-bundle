<?php

namespace Atournayre\AcceptanceBundle\Listener;

use Atournayre\AcceptanceBundle\Exception\DateConversionException;
use Atournayre\AcceptanceBundle\Exception\DateTimeNullException;
use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class AcceptanceListener
{
    const TEMPLATE = '@AtournayreAcceptance/index.html.twig';
    const TEMPLATE_ERROR = '@AtournayreAcceptance/error.html.twig';

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
     * @var Environment
     */
    private $environment;

    public function __construct(ParameterBagInterface $parameterBag, Environment $environment)
    {
        $this->environment = $environment;
        $this->isEnabled = $parameterBag->get('atournayre_acceptance.is_enabled');
        $this->startDateTime = $parameterBag->get('atournayre_acceptance.start_date_time');
        $this->endDateTime = $parameterBag->get('atournayre_acceptance.end_date_time');
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->isEnabled) {
            try {
                if (is_null($this->startDateTime)) {
                    throw new DateTimeNullException('Start date time cannot be null, please provide start datetime.');
                }
                $startDateTime = $this->convertDateTime($this->startDateTime);

                if (is_null($this->endDateTime)) {
                    throw new DateTimeNullException('End date time cannot be null, please provide end datetime.');
                }
                $endDateTime = $this->convertDateTime($this->endDateTime);

                if ($this->acceptanceIsDisabledForToday($startDateTime, $endDateTime)) {
                    $responseContent = $this->setResponseContent(self::TEMPLATE, [
                        'start_date_time' => $startDateTime,
                    ]);
                    $this->setResponse($event, $responseContent);
                }
            } catch (DateConversionException | DateTimeNullException $exception) {
                $responseContent = $this->setResponseContent(self::TEMPLATE_ERROR, [
                    'message' => $exception->getMessage(),
                ]);
                $this->setResponse($event, $responseContent);
            } catch (Exception $exception) {
                $responseContent = $this->setResponseContent(self::TEMPLATE_ERROR);
                $this->setResponse($event, $responseContent);
            }
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
            throw new DateConversionException($dateTime);
        }
    }

    private function acceptanceIsDisabledForToday(DateTime $startDateTime, DateTime $endDateTime): bool
    {
        $currentDateTime = new DateTime();

        return $currentDateTime < $startDateTime
            || $endDateTime < $currentDateTime;
    }

    private function setResponseContent(string $template, array $options = []): string
    {
        return $this->environment->render($template, $options);
    }

    private function setResponse(RequestEvent $event, string $responseContent): void
    {
        $event->setResponse(new Response($responseContent));
    }
}
