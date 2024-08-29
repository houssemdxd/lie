<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?Room $room = null;

    /**
     * @var Collection<int, Hand>
     */
    #[ORM\OneToMany(targetEntity: Hand::class, mappedBy: 'player')]
    private Collection $hands;

    /**
     * @var Collection<int, Round>
     */
    #[ORM\OneToMany(targetEntity: Round::class, mappedBy: 'player')]
    private Collection $target;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $winner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rank = null;

    public function __construct()
    {
        $this->hands = new ArrayCollection();
        $this->target = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

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

    /**
     * @return Collection<int, Hand>
     */
    public function getHands(): Collection
    {
        return $this->hands;
    }

    public function addHand(Hand $hand): static
    {
        if (!$this->hands->contains($hand)) {
            $this->hands->add($hand);
            $hand->setPlayer($this);
        }

        return $this;
    }

    public function removeHand(Hand $hand): static
    {
        if ($this->hands->removeElement($hand)) {
            // set the owning side to null (unless already changed)
            if ($hand->getPlayer() === $this) {
                $hand->setPlayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Round>
     */
    public function getTarget(): Collection
    {
        return $this->target;
    }

    public function addTarget(Round $target): static
    {
        if (!$this->target->contains($target)) {
            $this->target->add($target);
            $target->setPlayer($this);
        }

        return $this;
    }

    public function removeTarget(Round $target): static
    {
        if ($this->target->removeElement($target)) {
            // set the owning side to null (unless already changed)
            if ($target->getPlayer() === $this) {
                $target->setPlayer(null);
            }
        }

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(?string $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(?string $rank): static
    {
        $this->rank = $rank;

        return $this;
    }
}
