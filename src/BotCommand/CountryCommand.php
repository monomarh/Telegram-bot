<?php

declare(strict_types=1);

namespace App\BotCommand;

class CountryCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'country';

    /** @var string */
    protected $description = 'To get or set your country';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());
        $userLocation = $this->getUserLocation($user);

        if ($arguments) {
            $userLocation->setCountry($arguments);

            $this->getEntityManager()->flush();
        }

        $this->replyWithMessage([
            'text' => $user->getLocation()->getCountry() ?? sprintf(self::REQUEST_MESSAGE, $this->getName())
        ]);
    }
}
