<?php

namespace Mpp\UniversignBundle\Controller;

use Mpp\UniversignBundle\Event\UniversignCallbackEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallbackController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/universign/callback", name="mpp_universign_callback", methods="GET")
     */
    public function process(Request $request)
    {
        $status = $request->query->get('status');
        $transactionId = $request->query->get('id');
        $this->logger->info(sprintf('[Universign callback] %s Transaction id: %s', $status, $transactionId));

        $eventName = $event = UniversignCallbackEvent::TRANSACTION_INVALID;
        if (UniversignCallbackEvent::VALID === $status) {
            $eventName = UniversignCallbackEvent::TRANSACTION_VALID;
        }

        $event = new UniversignCallbackEvent($transactionId);

        $this->dispatcher->dispatch($event, $eventName);

        return new Response();
    }
}
