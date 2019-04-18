<?php

declare(strict_types = 1);

namespace App\Controller;

use BotMan\BotMan\BotMan;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use BotMan\Drivers\Telegram\TelegramDriver;

/**
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    private const SECRET_TOKEN = '818997148:AAGFFXJdbgkDX_Rms8eAR0xNygSFoEMRf10';

    /**
     * @param Request $request
     */
    public function indexAction(Request $request): void
    {
        $config = [
             'telegram' => [
                'token' => self::SECRET_TOKEN
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
