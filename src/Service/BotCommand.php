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
}