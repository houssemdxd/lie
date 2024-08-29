<?php

namespace App\Entity;

use App\Repository\TimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeRepository::class)]
class Time
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeround = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lie = null;

    #[ORM\ManyToOne(inversedBy: 'times')]
    private ?Room $room = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeround(): ?\DateTimeInterface
    {
        return $this->timeround;
    }

    public function setTimeround(?\DateTimeInterface $timeround): static
    {
        $this->timeround = $timeround;

        return $this;
    }

    public function isLie(): ?bool
    {
        return $this->lie;
    }

    public function setLie(?bool $lie): static
    {
        $this->lie = $lie;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }
}
