<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateBlogged;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInsert;

    /**
     * @ORM\ManyToOne(targetEntity=visit::class, inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $visit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateBlogged(): ?\DateTimeInterface
    {
        return $this->dateBlogged;
    }

    public function setDateBlogged(\DateTimeInterface $dateBlogged): self
    {
        $this->dateBlogged = $dateBlogged;

        return $this;
    }

    public function getDateInsert(): ?\DateTimeInterface
    {
        return $this->dateInsert;
    }

    public function setDateInsert(\DateTimeInterface $dateInsert): self
    {
        $this->dateInsert = $dateInsert;

        return $this;
    }

    public function getVisit(): ?visit
    {
        return $this->visit;
    }

    public function setVisit(?visit $visit): self
    {
        $this->visit = $visit;

        return $this;
    }
}
