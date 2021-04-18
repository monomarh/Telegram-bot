<?php

declare(strict_types=1);

namespace App\BotCommand;

use App\Entity\Location;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Finder;
use Telegram\Bot\Commands\Command;

abstract class BaseCommand extends Command
{
    protected EntityManagerInterface $entityManager;
    protected const REQUEST_MESSAGE = 'Please add your %s to end of the command';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getUser($telegramUserId): User
    {
        $user = $this->getEntityManager()
            ->getRepository(User::class)
            ->findOneByTelegramUserId($telegramUserId);

        if (!$user) {
            $user = new User();

            $this->getEntityManager()
                ->persist($user->setTelegramUserId($telegramUserId));
        }

        return $user;
    }

    protected function getUserLocation(User $user): Location
    {
        $location = $user->getLocation();

        if (!$location) {
            $location = new Location();
            $user->setLocation($location);

            $this->entityManager->persist($location);
        }

        return $location;
    }

    /**
     * Magic Method to handle all ReplyWith Methods.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed|string
     */
    public function __call($method, $arguments)
    {
        $action = substr($method, 0, 9);
        if ($action === 'replyWith') {
            $reply_name = ucwords(substr($method, 9));
            $methodName = sprintf("send%s", $reply_name);

            if (!method_exists($this->telegram, $methodName)) {
                return 'Method Not Found';
            }

            $chat_id = $this->update->getMessage()->getChat()->getId();
            $params = array_merge(compact('chat_id'), $arguments[0]);

            return call_user_func([$this->telegram, $methodName], $params);
        }

        return 'Method Not Found';
    }

    public static function getCommandClasses(): array
    {
        foreach ((new Finder())->files()->in(__DIR__) as $file) {
            $filenameWithoutExtension = $file->getFilenameWithoutExtension();

            if ($filenameWithoutExtension === 'BaseCommand') {
                continue;
            }

            $classes[] = sprintf(
                "%s\\%s",
                __NAMESPACE__,
                $filenameWithoutExtension
            );
        }

        return $classes ?? [];
    }
}
