<?php

namespace Atournayre\AcceptanceBundle\Listener;

use Atournayre\AcceptanceBundle\Exception\DateConversionException;
use Atournayre\AcceptanceBundle\Exception\DateTimeNullException;
use Atournayre\AcceptanceBundle\Service\AcceptanceService;
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
    /**
     * @var AcceptanceService
     */
    private $acceptanceService;

    public function __construct(ParameterBagInterface $parameterBag, Environment $environment, AcceptanceService $acceptanceService)
    {
        $this->environment = $environment;
        $this->isEnabled = $parameterBag->get('atournayre_acceptance.is_enabled');
        $this->startDateTime = $parameterBag->get('atournayre_acceptance.start_date_time');
        $this->endDateTime = $parameterBag->get('atournayre_acceptance.end_date_time');
        $this->acceptanceService = $acceptanceService;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->isEnabled) {
            try {
                if (is_null($this->startDateTime)) {
                    throw new DateTimeNullException('Start date time cannot be null, please provide start datetime.');
                }
                $startDateTime = $this->acceptanceService->convertDateTime($this->startDateTime);

                if (is_null($this->endDateTime)) {
                    throw new DateTimeNullException('End date time cannot be null, please provide end datetime.');
                }
                $endDateTime = $this->acceptanceService->convertDateTime($this->endDateTime);

                if ($this->acceptanceService->isDisabledForToday($startDateTime, $endDateTime)) {
                    $responseContent = $this->environment->render(self::TEMPLATE, [
                        'start_date_time' => $startDateTime,
                    ]);
                    $event->setResponse(new Response($responseContent));
                }
            } catch (DateConversionException | DateTimeNullException $exception) {
                $responseContent = $this->environment->render(self::TEMPLATE_ERROR, [
                    'message' => $exception->getMessage(),
                ]);
                $event->setResponse(new Response($responseContent));
            } catch (Exception $exception) {
                $responseContent = $this->environment->render(self::TEMPLATE_ERROR);
                $event->setResponse(new Response($responseContent));
            }
        }
    }
}
