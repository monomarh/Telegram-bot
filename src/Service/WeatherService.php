<?php

declare(strict_types = 1);

namespace App\Service;

use Forecast\Forecast;
use stdClass;

class WeatherService
{
    /** @var Forecast */
    private Forecast $forecast;

    /** @var StdClass */
    private StdClass $wholeWeather;

    public function __construct()
    {
        $this->forecast = new Forecast($_ENV['WEATHER_TOKEN']);
    }

    /**
     * @return stdClass
     */
    public function getWholeWeather(): stdClass
    {
        return $this->forecast->get(
            '53.9',
            '27.56667',
            null,
            [
                'units' => 'si',
            ]
        );
    }
}
