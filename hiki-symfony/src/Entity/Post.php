<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'finder', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $finder;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $item = null;

    #[ORM\Column(name: 'created_at')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(type: 'string', length: 15)]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $picture = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getFinder(): User { return $this->finder; }
    public function setFinder(User $finder): static { $this->finder = $finder; return $this; }
    public function getItem(): ?string { return $this->item; }
    public function setItem(?string $item): static { $this->item = $item; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getPlace(): ?string { return $this->place; }
    public function setPlace(?string $place): static { $this->place = $place; return $this; }
    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $phone): static { $this->phone = $phone; return $this; }
    public function getPicture(): ?string { return $this->picture; }
    public function setPicture(?string $picture): static { $this->picture = $picture; return $this; }
}
