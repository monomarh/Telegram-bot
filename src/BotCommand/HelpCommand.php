<?php

declare(strict_types=1);

namespace App\BotCommand;

class HelpCommand extends BaseCommand
{
    /** @var string */
    protected $name = 'help';

    /** @var string */
    protected $description = 'To get you started';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        $commands = $this->getTelegram()->getCommands();

        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }

        $this->replyWithMessage(['text' => $response]);
    }
}
