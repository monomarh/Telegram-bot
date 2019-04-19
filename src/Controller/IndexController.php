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

        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create($config);

        $botman->hears('hello', static function(BotMan $bot) {
            $bot->reply(var_export($bot, true));
        });

        $botman->hears('call me {name}', static function(BotMan $bot, string $name) {
            $bot->reply(sprintf('Hello %s.', $name));
        });

        $botman->hears('i live in {city}', static function(BotMan $bot, string $city) {
            $bot->reply(sprintf('Weather in %s as ass.', $city));
        });

        $botman->hears('i was born {birthday}', static function(BotMan $bot, string $birthday) {
            $birthdayDate = new \DateTime($birthday);
            $bot->reply(sprintf('You\'re %s years old.', $birthdayDate->diff(new \DateTime())->format('%Y')));
        });

        $botman->hears('i was born {birthday}', static function(BotMan $bot, string $birthday) {
            $birthdayDate = new \DateTime($birthday);
            $bot->reply(sprintf('You\'re %s years old.', $birthdayDate->diff(new \DateTime())->format('%Y')));
        });

        $botman->hears('help', static function(BotMan $bot) {
            $commandList =
                'help - command list with description' . PHP_EOL .
                'call me "YOUR NAME" - enter your name instead of "YOUR NAME", after that the bot will call you in a new way' . PHP_EOL .
                'i live in "YOUR CITY" - enter your city instead of "YOUR CITY", after that the bot will send the weather for this city' . PHP_EOL;
            $bot->reply(sprintf('You\'re %s years old.', $commandList));
        });

        $botman->fallback(static function(BotMan $bot) {
            $bot->reply('Sorry, I did not understand these commands. Type help for command list');
        });

        $botman->listen();

        return new Response('All good');
    }
}
