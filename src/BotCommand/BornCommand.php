<?php

declare(strict_types=1);

namespace App\BotCommand;

use DateTimeImmutable;

class BornCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'born';

    /** @var string */
    protected $description = 'To get or set your birthday date';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());

        if ($arguments && $this->isValidDate($arguments)) {
            $user->setBirthday(new DateTimeImmutable($arguments));

            $this->getEntityManager()->flush();
        }

        $this->replyWithMessage([
            'text' => $user->getBirthday()
                ? $user->getBirthday()->format('Y-m-d') . ' Age: ' . $user->getAge()
                : sprintf(self::REQUEST_MESSAGE, $this->getName())
        ]);
    }

    private function isValidDate(string $date): bool
    {
        return strtotime($date) && date('Y-m-d', strtotime($date)) === $date;
    }
}
