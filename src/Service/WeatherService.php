<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use Forecast\Forecast;
use stdClass;

class WeatherService
{
    private Forecast $forecast;
    private StdClass $wholeWeather;

    public function __construct()
    {
        $this->forecast = new Forecast($_ENV['WEATHER_TOKEN']);
    }

    public function getWholeWeather(Location $location): stdClass
    {
        return $this->forecast->get(
            ...$location->getGeometry(),
            ...[
                null,
                [
                    'units' => 'si',
                ]
           ],
        );
    }
}
