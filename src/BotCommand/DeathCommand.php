<?php

declare(strict_types=1);

namespace App\BotCommand;

use App\Service\DeathDayService;

class DeathCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'death';

    /** @var string */
    protected $description = 'To get your deathday date';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());

        if (!$user->getDeathday()) {
            try {
                $user->setDeathday(
                    (new DeathDayService())->getDeathDay(
                        $user->getGender(),
                        $user->getLocation()->getCountry(),
                        $user->getAge()
                    )
                );
            } catch (\Exception $exception) {
                $this->replyWithMessage(
                    [
                        'text' => "Something went wrong, try again later.\n
                            You need to set your gender, country and birthday"
                    ]
                );
            }

            $this->getEntityManager()->flush();
        }

        $this->replyWithMessage([
            'text' => $user->getDeathday()
                ? $user->getDeathday()->format('Y-m-d')
                : sprintf(self::REQUEST_MESSAGE, $this->getName())
        ]);
    }

    private function isValidDate(string $date): bool
    {
        return strtotime($date) && date('Y-m-d', strtotime($date)) === $date;
    }
}
