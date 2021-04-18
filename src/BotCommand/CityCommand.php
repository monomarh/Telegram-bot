<?php

declare(strict_types=1);

namespace App\BotCommand;

class CityCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'city';

    /** @var string */
    protected $description = 'To get or set your city';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());
        $userLocation = $this->getUserLocation($user);

        if ($arguments) {
            $userLocation->setCity($arguments);

            $this->getEntityManager()->flush();
        }

        $this->replyWithMessage([
            'text' => $user->getLocation()->getCity() ?? sprintf(self::REQUEST_MESSAGE, $this->getName())
        ]);
    }
}
