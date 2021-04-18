<?php

declare(strict_types=1);

namespace App\BotCommand;

class NameCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'name';

    /** @var string */
    protected $description = 'To get or set your name';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());

        if ($arguments) {
            $user->setName($arguments);

            $this->getEntityManager()->flush();
        }

        $this->replyWithMessage([
            'text' => $user->getName() ?? sprintf(self::REQUEST_MESSAGE, $this->getName())
        ]);
    }
}
