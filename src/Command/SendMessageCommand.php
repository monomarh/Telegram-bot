<?php

declare(strict_types = 1);

namespace App\Command;

use App\Repository\UserRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMessageCommand extends Command
{
    /** @var string  */
    protected static $defaultName = 'send:message';

    /** @var Botman */
    private $botMan;

    /** @var UserRepository  */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        
        $this->userRepository = $userRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = [
            'telegram' => [
                'token' => $_ENV['SECRET_TOKEN']
            ]
        ];

        DriverManager::loadDriver(TelegramDriver::class);
        $this->botMan = BotManFactory::create($config);

        $users = $this->userRepository->findAll();

        $dayToLive = (new DateTime('now'))->diff(new DateTime('2071-12-19'))->format('%a');

        foreach ($users as $user) {
            try {
                $this->botMan->say(
                    sprintf(
                        'Hello %s. You have %s days to live',
                        $user->getName(),
                        $dayToLive
                    ),
                    $user->getUserId(),
                    TelegramDriver::class
                );
            } catch (Exception $e) {
                $output->write($e);
            }
        }
    }
}
