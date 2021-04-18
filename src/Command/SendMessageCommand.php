<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\WeatherService;
use App\Util\BotInterface;
use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMessageCommand extends Command
{
    protected static $defaultName = 'send:message';

    private BotInterface $bot;
    private UserRepository $userRepository;
    private WeatherService $weatherService;

    public function __construct(
        UserRepository $userRepository,
        WeatherService $weatherService,
        BotInterface $bot
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->weatherService = $weatherService;
        $this->bot = $bot;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        $currentDate = new DateTime();

        foreach ($users as $user) {
            if ($user->getLocation()) {
                $wholeWeather = $this->weatherService->getWholeWeather($user->getLocation())->daily;
                $todayWeather = $wholeWeather->data[0];

                try {
                    $this->bot->sendTextMessage(
                        sprintf(
                            'Hello %s. You have %s days to live. Probability of precipitation: %s%%. High temperature: %s℃. Low temperature: %s℃. Wind speed: %sm/s.',
                            $user->getName(),
                            $user->getDaysToLive(),
                            $todayWeather->precipProbability * 100,
                            $todayWeather->temperatureHigh,
                            $todayWeather->temperatureLow,
                            $todayWeather->windSpeed
                        ) . (($currentDate->format('D') === 'Mon') ? sprintf(' %s', $wholeWeather->summary) : ''),
                        $user->getTelegramUserId(),
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
