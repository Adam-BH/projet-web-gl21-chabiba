<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'adresses')]
class Address
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private string $id;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $lat = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $lon = null;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string { return $this->id; }
    public function getLat(): ?float { return $this->lat; }
    public function setLat(?float $lat): static { $this->lat = $lat; return $this; }
    public function getLon(): ?float { return $this->lon; }
    public function setLon(?float $lon): static { $this->lon = $lon; return $this; }
}
