<?php

declare(strict_types=1);

namespace App\BotCommand;

class InfoCommand extends BaseCommand
{
    protected const INFO_MESSAGE = 'Hello %s, your live in %s and you\'re %s years old. You have %s days to live';

    /** @var string */
    protected $name = 'info';

    /** @var string */
    protected $description = 'To get full information about you';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());

        $text = sprintf(
            self::INFO_MESSAGE,
            $user->getName(),
            $user->getLocation() ? $user->getLocation()->getFullAddress() : '?',
            $user->getBirthday()
                ? $user->getBirthday()->diff(new \DateTime())->format('%Y')
                : '?',
            $user->getDaysToLive()
        );

        $this->replyWithMessage([
            'text' => $text ?? 'You didn\'t entered any information'
        ]);
    }
}
