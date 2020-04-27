<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\Location;
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
     * @param Location $location
     *
     * @return stdClass
     */
    public function getWholeWeather(Location $location): stdClass
    {
        return $this->forecast->get(
            $location->getLatitude(),
            $location->getLongitude(),
            null,
            [
                'units' => 'si',
            ],
        );
    }
}
