<?php

declare(strict_types=1);

namespace App\BotCommand;

class SecretCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'secret';

    /** @var string */
    protected $description = 'Secret for birthday';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $this->replyWithMessage([
            'text' => $arguments === '16081993' 
                ? 'Happy birthday. Code phrase: "Daniel Defoe"' 
                : 'Oh, sorry, incorrect. But anyway - Happy Birthday'
        ]);
    }
}
