<?php

declare(strict_types = 1);

namespace App\Controller;

use BotMan\BotMan\BotMan;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TelegramBot\Api\Client;
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
    private $siteUrl;

    /**
     * @var string
     */
    private const SECRET_TOKEN = '818997148:AAGFFXJdbgkDX_Rms8eAR0xNygSFoEMRf10';

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $this->siteUrl = 'https://' . $request->getHttpHost();

//        try {
//            $bot = new Client(self::SECRET_TOKEN);
//            $bot->setWebhook($this->siteUrl);
//
//            $bot->command('start', static function ($message) use ($bot) {
//                $bot->sendMessage($message->getChat()->getId(), 'Hello');
//
//
//            });
//
//            $bot->command('name', static function ($message) use ($bot) {
//                $name = str_replace('/name', '', $message->getText());
//                $bot->sendMessage($message->getChat()->getId(), 'Hello ' . $name);
//            });
//
//            $bot->command('help', static function ($message) use ($bot) {
//                $answer = 'Information about commands:' .
//                '/start - start(edit) initialization process' .
//                '/help - command list';
//
//                $bot->sendMessage($message->getChat()->getId(), $answer);
//            });
//
//            $bot->run();
//        } catch (\Exception $e) {
//            return new Response($e->getMessage());
//        }

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

        return new Response($this->siteUrl);
    }
}
