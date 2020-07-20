<?php

declare(strict_types = 1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\BotService;
use App\Service\WeatherService;
use BotMan\BotMan\BotMan;
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
    private BotMan $botMan;

    /** @var UserRepository */
    private UserRepository $userRepository;

    /** @var WeatherService */
    private WeatherService $weatherService;

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
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        $currentDate = new DateTime();
        $dayToLive = $currentDate->diff(new DateTime('2071-12-19'))->format('%a');

        foreach ($users as $user) {
            if ($user->getLocation()) {
                $wholeWeather = $this->weatherService->getWholeWeather($user->getLocation())->daily;
                $todayWeather = $wholeWeather->data[0];

                try {
                    $this->botMan->say(
                        sprintf(
                            'Hello %s. You have %s days to live. Probability of precipitation: %s%%. High temperature: %s℃. Low temperature: %s℃. Wind speed: %sm/s.',
                            $user->getName(),
                            $dayToLive,
                            $todayWeather->precipProbability * 100,
                            $todayWeather->temperatureHigh,
                            $todayWeather->temperatureLow,
                            $todayWeather->windSpeed
                        ) . (($currentDate->format('D') === 'Mon') ? sprintf(' %s', $wholeWeather->summary) : ''),
                        $user->getTelegramUserId(),
                        TelegramDriver::class
                    );
                } catch (Exception $e) {
                    $output->writeln(json_encode($e, JSON_THROW_ON_ERROR));

                    return 1;
                }
            }
        }

        return 0;
    }
}
