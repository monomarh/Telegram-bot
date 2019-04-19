<?php

declare(strict_types = 1);

namespace App\Service;

use BotMan\BotMan\BotMan;

class BotCommand
{
    /**
     * @param BotMan $bot
     */
    public function hello(BotMan $bot): void
    {
        $bot->reply($bot->getUser()->getId());
    }

    /**
     * @param BotMan $bot
     * @param string $name
     */
    public function name(BotMan $bot, string $name): void
    {
        $bot->reply(sprintf('Hello %s.', $name));
    }

    /**
     * @param BotMan $bot
     * @param string $city
     */
    public function city(BotMan $bot, string $city): void
    {
        $bot->reply(sprintf('Weather in %s as ass.', $city));
    }

    /**
     * @param BotMan $bot
     */
    public function help(BotMan $bot): void
    {
        $commandList =
            'help - command list with description' . PHP_EOL . PHP_EOL .
            'call me "YOUR NAME" - enter your name instead of "YOUR NAME", after that the bot will call you in a new way' . PHP_EOL . PHP_EOL .
            'i live in "YOUR CITY" - enter your city instead of "YOUR CITY", after that the bot will send the weather for this city' . PHP_EOL;
        $bot->reply($commandList);
    }

    /**
     * @param BotMan $bot
     * @param string $birthday
     *
     * @throws \Exception
     */
    public function birthday(BotMan $bot, string $birthday): void
    {
        $birthdayDate = new \DateTime($birthday);
        $bot->reply(sprintf('You\'re %s years old.', $birthdayDate->diff(new \DateTime())->format('%Y')));
    }

    public function fallback(BotMan $bot): void
    {
        $bot->reply('Sorry, I did not understand these commands. Type help for command list');
    }
}