<?php

declare(strict_types = 1);

namespace App\Controller;

use BotMan\BotMan\BotMan;
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

        dump($config);

        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create($config);

        $botman->hears('hello', static function (BotMan $bot) {
            $bot->reply('Hello yourself.');
        });

        $botman->listen();

        return new Response('All good');
    }
}
