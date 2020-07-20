<?php

declare(strict_types=1);

namespace App\Service;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

class BotService
{
    /** @var BotMan */
    private BotMan $botMan;

    public function __construct()
    {
        DriverManager::loadDriver(TelegramDriver::class);
        $this->botMan = BotManFactory::create(self::getConfigs());
    }

    /**
     * @return BotMan
     */
    public function getBot(): BotMan
    {
        return $this->botMan;
    }

    /**
     * @return array[]
     */
    private static function getConfigs(): array
    {
        return [
            'telegram' => [
                'token' => $_ENV['SECRET_TOKEN']
            ]
        ];
    }
}
