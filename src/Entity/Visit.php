<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 */
class Visit
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $dateVisitedFrom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateVisitedTill;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInserted;

    /**
     * @ORM\ManyToOne(targetEntity=country::class, inversedBy="visits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="visit")
     */
    private $blogs;

    public function __construct()
    {
        $this->blogs = new ArrayCollection();
    }

    public function getDateInserted(): ?\DateTimeInterface
    {
        return $this->dateInserted;
    }

    public function setDateInserted(\DateTimeInterface $dateInserted): self
    {
        $this->dateInserted = $dateInserted;

        return $this;
    }

    public function getCountry(): ?country
    {
        return $this->country;
    }

    public function setCountry(?country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setVisit($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getVisit() === $this) {
                $blog->setVisit(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->getId(),
            'title'           => $this->getTitle(),
            'description'     => $this->getDescription(),
            'dateVisitedFrom' => $this->getDateVisitedFrom(),
            'dateVisitedTill' => $this->getDateVisitedTill()
        ];
    }

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateVisitedFrom(): ?\DateTimeInterface
    {
        return $this->dateVisitedFrom;
    }

    public function setDateVisitedFrom(\DateTimeInterface $dateVisitedFrom): self
    {
        $this->dateVisitedFrom = $dateVisitedFrom;

        return $this;
    }

    public function getDateVisitedTill(): ?\DateTimeInterface
    {
        return $this->dateVisitedTill;
    }

    public function setDateVisitedTill(\DateTimeInterface $dateVisitedTill): self
    {
        $this->dateVisitedTill = $dateVisitedTill;

        return $this;
    }
}
