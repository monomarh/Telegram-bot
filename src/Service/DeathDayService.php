<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Client;

class DeathDayService
{
    private const MOUTHS = 12;

    public function getDeathDay(string $gender, string $country, string $userAge): DateTimeInterface
    {
        $client = new Client();
        $now = (new DateTime())->format('Y-m-d');

        $deathInfo = json_decode(
            (string) $client
                ->get(sprintf(
                    "%s/%s/%s/%s/%s/",
                    $_ENV['POPULATION_API'],
                    $gender,
                    $country,
                    $now,
                    $userAge
                ))->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $deathDay = (int) ceil(self::MOUTHS * $deathInfo['remaining_life_expectancy']);

        return (new DateTimeImmutable())->add(new \DateInterval("P{$deathDay}M"));
    }
}
