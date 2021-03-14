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
     * @var Environment
     */
    private $environment;

    /**
     * @var AcceptanceService
     */
    private $acceptanceService;

    public function __construct(Environment $environment, AcceptanceService $acceptanceService)
    {
        $this->environment = $environment;
        $this->acceptanceService = $acceptanceService;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->acceptanceService->isServiceEnabled()) {
            try {
                if ($this->acceptanceService->isCurrentlyDisabled()) {
                    $responseContent = $this->environment->render(self::TEMPLATE, [
                        'start_date_time' => $this->acceptanceService->getStartDateTime(),
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
