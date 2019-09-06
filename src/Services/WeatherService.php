<?php

declare(strict_types = 1);

namespace App\Services;

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
use Cmfcmf\OpenWeatherMap\Util\Temperature;
use Http\Factory\Guzzle\RequestFactory;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class WeatherService
{
    /** @var string  */
    private $language = 'en';

    /** @var string  */
    private $units = 'metric';

    /** @var OpenWeatherMap  */
    private $owm;

    public function __construct()
    {
        $httpRequestFactory = new RequestFactory();
        $httpClient = GuzzleAdapter::createWithConfig([]);

        $this->owm = new OpenWeatherMap($_ENV['OPEN_WEATHER_TOKEN'], $httpClient, $httpRequestFactory);
    }

    /**
     * @return Temperature
     */
    public function getTemperature(): Temperature
    {
        try {
            $weather = $this->owm->getWeather('Minsk', $this->units, $this->language);
        } catch (OWMException $e) {
            echo 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
        } catch (\Exception $e) {
            echo 'General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
        }

        return $weather->temperature;
    }
}
