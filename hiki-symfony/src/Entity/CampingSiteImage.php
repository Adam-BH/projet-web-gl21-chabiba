<?php

namespace App\Entity;

use App\Repository\CampingSiteImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampingSiteImageRepository::class)]
#[ORM\Table(name: 'camping_site_images')]
class CampingSiteImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CampingSite::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: 'site_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private CampingSite $site;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

    #[ORM\Column(name: 'sort_order', type: 'integer', options: ['default' => 0])]
    private int $sortOrder = 0;

    public function getId(): ?int { return $this->id; }
    public function getSite(): CampingSite { return $this->site; }
    public function setSite(CampingSite $site): static { $this->site = $site; return $this; }
    public function getPath(): string { return $this->path; }
    public function setPath(string $path): static { $this->path = $path; return $this; }
    public function getSortOrder(): int { return $this->sortOrder; }
    public function setSortOrder(int $sortOrder): static { $this->sortOrder = $sortOrder; return $this; }
}
