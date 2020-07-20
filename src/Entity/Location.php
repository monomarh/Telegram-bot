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
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country = null;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private ?float $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
     */
    private ?float $longitude;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getFullAddress(): string
    {
        return sprintf(
            '%s, %s',
            urlencode($this->getCity()),
            urlencode($this->getCountry())
        );
    }

    /**
     * @param array $geometry
     */
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
