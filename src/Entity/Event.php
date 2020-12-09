<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $theme;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime_start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime_end;

    /**
     * @ORM\Column(type="text")
     */
    private $place;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDatetimeStart(): ?\DateTimeInterface
    {
        return $this->datetime_start;
    }

    public function setDatetimeStart(\DateTimeInterface $datetime_start): self
    {
        $this->datetime_start = $datetime_start;

        return $this;
    }

    public function getDatetimeEnd(): ?\DateTimeInterface
    {
        return $this->datetime_end;
    }

    public function setDatetimeEnd(\DateTimeInterface $datetime_end): self
    {
        $this->datetime_end = $datetime_end;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }
}
