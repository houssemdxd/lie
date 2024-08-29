<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cards = null;

    #[ORM\ManyToOne(inversedBy: 'tables')]
    private ?Round $round = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCards(): ?string
    {
        return $this->cards;
    }

    public function setCards(string $cards): static
    {
        $this->cards = $cards;

        return $this;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): static
    {
        $this->round = $round;

        return $this;
    }
}
