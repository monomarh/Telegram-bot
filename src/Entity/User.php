<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Users")
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private string $gender;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $birthday;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $deathday;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private ?string $locale = 'en';

    /**
     * @ORM\Column(type="integer")
     */
    private int $telegramUserId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", cascade={"persist"})
     */
    private ?Location $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function getAge(): string
    {
        return $this->getBirthday()
            ? (new \DateTimeImmutable())->diff($this->getBirthday())->format('%Yy%mm')
            : '?';
    }

    public function setBirthday(DateTimeInterface $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getDeathday(): ?DateTimeInterface
    {
        return $this->deathday;
    }

    public function setDeathday(?DateTimeInterface $deathday): void
    {
        $this->deathday = $deathday;
    }

    public function getDaysToLive(): string
    {
        return $this->getDeathday()
            ? (new \DateTimeImmutable())->diff($this->getDeathday())->format('%a')
            : '?';
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getTelegramUserId(): int
    {
        return $this->telegramUserId;
    }

    public function setTelegramUserId(int $telegramUserId): self
    {
        $this->telegramUserId = $telegramUserId;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }
}
