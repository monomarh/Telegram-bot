<?php

declare(strict_types = 1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\BotService;
use App\Service\WeatherService;
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
    /** @var string */
    protected static $defaultName = 'send:message';

    /** @var Botman */
    private $botMan;

    /** @var UserRepository */
    private $userRepository;

    /** @var WeatherService */
    private $weatherService;

    /**
     * @param UserRepository $userRepository
     * @param WeatherService $weatherService
     * @param BotService $botService
     */
    public function __construct(
        UserRepository $userRepository,
        WeatherService $weatherService,
        BotService $botService
    ) {
        parent::__construct();
        
        $this->userRepository = $userRepository;
        $this->weatherService = $weatherService;
        $this->botMan = $botService->getBot();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->userRepository->findAll();

        $dayToLive = (new DateTime('now'))->diff(new DateTime('2071-12-19'))->format('%a');

        foreach ($users as $user) {
            try {
                $this->botMan->say(
                    sprintf(
                        'Hello %s. You have %s days to live. Now %s outside',
                        $user->getName(),
                        $dayToLive,
                        $this->weatherService->getTemperature()
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
