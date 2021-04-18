<?php

declare(strict_types=1);

namespace App\Util;

interface BotInterface
{
    public function init(): void;
    public function sendTextMessage(string $message, int $telegramUserId): void;
}
