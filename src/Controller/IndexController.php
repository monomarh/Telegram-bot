<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Service\BotCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use BotMan\Drivers\Telegram\TelegramDriver;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        $config = [
             'telegram' => [
                'token' => $_ENV['SECRET_TOKEN']
             ]
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create($config);

        $botman->hears('hello', BotCommand::class . '@hello');
        $botman->hears('call me {name}', BotCommand::class . '@name');
        $botman->hears('i live in {city}', BotCommand::class . '@city');
        $botman->hears('i was born {birthday}', BotCommand::class . '@birthday');
        $botman->hears('help', BotCommand::class . '@help');

        $botman->fallback(BotCommand::class . '@fallback');

        $botman->listen();

        return new Response('All good');
    }
}
