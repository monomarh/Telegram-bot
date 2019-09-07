<?php

declare(strict_types = 1);

namespace App\Services;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

class BotService
{
    /** @var BotMan */
    private $botMan;

    public function __construct()
    {
        DriverManager::loadDriver(TelegramDriver::class);
        $this->botMan = BotManFactory::create(self::getConfigs());
    }

    /**
     * @return BotMan
     */
    public function getBot()
    {
        return $this->botMan;
    }

    private static function getConfigs()
    {
        return [
            'telegram' => [
                'token' => $_ENV['SECRET_TOKEN']
            ]
        ];
    }
}