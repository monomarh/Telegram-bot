<?php

declare(strict_types = 1);

namespace App\Service;

use DateTime;
use Forecast\Forecast;
use stdClass;

class WeatherService
{
    /** @var Forecast */
    private $forecast;
    
    public function __construct()
    {
        $this->forecast = new Forecast($_ENV['WEATHER_TOKEN']);
    }

    /**
     * @return stdClass
     */
    public function getTemperature(): stdClass
    {
        $weather = $this->forecast->get('53.9', '27.56667');

        return $weather;
    }
}
