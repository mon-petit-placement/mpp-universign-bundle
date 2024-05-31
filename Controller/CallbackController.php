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
    private LoggerInterface $logger;

    private EventDispatcherInterface $dispatcher;

    public function __construct(LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    #[Route('/universign/callback', name: 'mpp_universign_callback', methods: 'GET')]
    public function process(Request $request): Response
    {
        $status = $request->query->get('status');
        $transactionId = $request->query->get('id');
        $indexSigner = $request->query->get('signer');
        $this->logger->info(sprintf('[Universign callback] Transaction "%s" with status "%s" and signer "%s"', $transactionId, $status, $indexSigner));

        $event = new UniversignCallbackEvent($transactionId, $indexSigner, $status);

        $this->dispatcher->dispatch($event);

        return new Response();
    }
}
