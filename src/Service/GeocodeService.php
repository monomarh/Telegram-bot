<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use Exception;
use OpenCage\Geocoder\Geocoder;
use Psr\Log\LoggerInterface;

class GeocodeService
{
    /** @var Geocoder */
    private Geocoder $geocoder;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->geocoder = new Geocoder($_ENV['GEOCODE_TOKEN']);
        $this->logger = $logger;
    }

    /**
     * @param Location $location
     *
     * @return array
     */
    public function getGeometry(Location $location): array
    {
        try {
            $result = $this->geocoder->geocode(
                $location->getFullAddress(),
                $this->getOptionsList()
            );

            if ($result['status']['message'] === 'OK') {
                return $result['results'][0]['geometry'];
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    private function getOptionsList(): array
    {
        return [
            'pretty' => true,
            'limit' => true,
            'no_record' => true,
            'no_annotations' => true,
            'no_dedupe' => true,
            '_type' => 'state',
        ];
    }
}
