<?php

namespace App\Entity;

use App\Service\GeocodeService;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Monolog\Logger;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private ?float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
     */
    private ?float $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getFullAddress(): string
    {
        return sprintf(
            '%s, %s',
            urlencode($this->getCity()),
            urlencode($this->getCountry())
        );
    }

    public function setGeometry(array $geometry): void
    {
        if (!isset($geometry['latitude'], $geometry['longitude'])
            && !isset($geometry['lat'], $geometry['lng'])
        ) {
            throw new InvalidArgumentException('Latitude and longitude must be set');
        }

        $this->setLatitude($geometry['latitude'] ?? $geometry['lat']);
        $this->setLongitude($geometry['longitude'] ?? $geometry['lng']);
    }

    /**
     * @return float[]
     */
    public function getGeometry(): array
    {
        if ($this->getLatitude() === null || $this->getLongitude() === null) {
            $this->setGeometry((new GeocodeService(new Logger('Geocode')))->getGeometry($this));
        }

        return [(float) $this->getLatitude(), (float) $this->getLongitude()];
    }
}
