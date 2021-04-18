<?php

declare(strict_types=1);

namespace App\Service;

use App\BotCommand\BaseCommand;
use App\Util\BotInterface;
use Doctrine\ORM\EntityManagerInterface;
use Telegram\Bot\Api;

class Bot extends Api implements BotInterface
{
    private EntityManagerInterface $entityManager;

    public function init(): void
    {
        foreach (BaseCommand::getCommandClasses() as $class) {
            $this->addCommand(new $class($this->getEntityManager()));
        }

        $this->commandsHandler(true);
    }

    public function sendTextMessage(string $message, int $telegramUserId): void
    {
        $this->sendMessage(
            [
                'chat_id' => $telegramUserId,
                'text' => $message
            ]
        );
    }

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
