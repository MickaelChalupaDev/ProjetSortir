<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('liste_villes')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups('liste_villes')]
    private ?string $nom = null;

    #[ORM\Column(length: 150)]
    #[Groups('liste_villes')]
    private ?string $rue = null;

    #[ORM\Column]
    #[Groups('liste_villes')]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Groups('liste_villes')]
    private ?float $longitude = null;

    #[ORM\OneToMany(mappedBy: 'lieu', targetEntity: Sortie::class)]
    private Collection $sorties;

    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ville $ville = null;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->setLieu($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getLieu() === $this) {
                $sorty->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
