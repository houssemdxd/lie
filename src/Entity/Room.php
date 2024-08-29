<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $numberParticipant = null;

    /**
     * @var Collection<int, Cards>
     */
    #[ORM\OneToMany(targetEntity: Cards::class, mappedBy: 'room')]
    private Collection $cards;

    /**
     * @var Collection<int, Player>
     */
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'room')]
    private Collection $players;

    /**
     * @var Collection<int, Round>
     */
    #[ORM\OneToMany(targetEntity: Round::class, mappedBy: 'room')]
    private Collection $rounds;

    #[ORM\Column(nullable: true)]
    private ?bool $ready = null;

    /**
     * @var Collection<int, Time>
     */
    #[ORM\OneToMany(targetEntity: Time::class, mappedBy: 'room')]
    private Collection $times;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $goal = null;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->rounds = new ArrayCollection();
        $this->times = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getNumberParticipant(): ?string
    {
        return $this->numberParticipant;
    }

    public function setNumberParticipant(string $numberParticipant): static
    {
        $this->numberParticipant = $numberParticipant;

        return $this;
    }

    /**
     * @return Collection<int, Cards>
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Cards $card): static
    {
        if (!$this->cards->contains($card)) {
            $this->cards->add($card);
            $card->setRoom($this);
        }

        return $this;
    }

    public function removeCard(Cards $card): static
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getRoom() === $this) {
                $card->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setRoom($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getRoom() === $this) {
                $player->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Round>
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function addRound(Round $round): static
    {
        if (!$this->rounds->contains($round)) {
            $this->rounds->add($round);
            $round->setRoom($this);
        }

        return $this;
    }

    public function removeRound(Round $round): static
    {
        if ($this->rounds->removeElement($round)) {
            // set the owning side to null (unless already changed)
            if ($round->getRoom() === $this) {
                $round->setRoom(null);
            }
        }

        return $this;
    }

    public function isReady(): ?bool
    {
        return $this->ready;
    }

    public function setReady(?bool $ready): static
    {
        $this->ready = $ready;

        return $this;
    }

    /**
     * @return Collection<int, Time>
     */
    public function getTimes(): Collection
    {
        return $this->times;
    }

    public function addTime(Time $time): static
    {
        if (!$this->times->contains($time)) {
            $this->times->add($time);
            $time->setRoom($this);
        }

        return $this;
    }

    public function removeTime(Time $time): static
    {
        if ($this->times->removeElement($time)) {
            // set the owning side to null (unless already changed)
            if ($time->getRoom() === $this) {
                $time->setRoom(null);
            }
        }

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): static
    {
        $this->goal = $goal;

        return $this;
    }
}
