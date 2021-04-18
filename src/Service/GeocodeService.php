<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use Exception;
use OpenCage\Geocoder\Geocoder;
use Psr\Log\LoggerInterface;

class GeocodeService
{
    private Geocoder $geocoder;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->geocoder = new Geocoder($_ENV['GEOCODE_TOKEN']);
        $this->logger = $logger;
    }

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
