<?php

declare(strict_types = 1);

namespace App\Controller;

use BotMan\BotMan\BotMan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use BotMan\Drivers\Telegram\TelegramDriver;

/**
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    public function indexAction(): void
    {
        $config = [
             'telegram' => [
                'token' => $_ENV['SECRET_TOKEN']
             ]
        ];

        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create($config);

        $botman->hears('hello', static function (BotMan $bot) {
            $bot->reply('Hello yourself.');
        });

        $botman->listen();
    }
}
