<?php

declare(strict_types=1);

namespace App\BotCommand;

class GenderCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'gender';

    /** @var string */
    protected $description = 'To get or set your gender';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $user = $this->getUser($this->getUpdate()->getMessage()->getChat()->getId());

        if ($arguments) {
            $gender = strtolower($arguments);

            if ($gender !== 'male' || $gender !== 'female') {
                $this->replyWithMessage(['text' => 'Allowed only male or female']);
                return;
            }

            $user->setGender($gender);

            $this->getEntityManager()->flush();
        }

        $this->replyWithMessage([
            'text' => $user->getName() ?? sprintf(self::REQUEST_MESSAGE, $this->getName())
        ]);
    }
}
