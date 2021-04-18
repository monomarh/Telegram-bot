<?php

declare(strict_types=1);

namespace App\Controller;

use App\Util\BotInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private BotInterface $bot;
    private LoggerInterface $logger;

    public function __construct(BotInterface $bot, LoggerInterface $logger)
    {
        $this->bot = $bot;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="index")
     */
    public function indexAction(): Response
    {
        try {
            $this->bot->init();

            return new Response('Success', 200);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return new Response('Unexpected Error. Try latter.', 500);
    }
}
