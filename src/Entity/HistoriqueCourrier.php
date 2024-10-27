<?php

namespace App\Entity;

use App\Repository\HistoriqueCourrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueCourrierRepository::class)]
class HistoriqueCourrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    #[ORM\Column(length: 50)]
    private ?string $expediteur = null;

    #[ORM\Column(nullable: true)]
    private ?array $destinataire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnvoie = null;

    #[ORM\Column]
    private ?bool $supprime = null;

    #[ORM\OneToMany(targetEntity: Courrier::class, mappedBy: 'historiqueCourrier')]
    private Collection $courrier;

    public function __construct()
    {
        $this->courrier = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getExpediteur(): ?string
    {
        return $this->expediteur;
    }

    public function setExpediteur(string $expediteur): static
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataire(): ?array
    {
        return $this->destinataire;
    }

    public function setDestinataire(?array $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(\DateTimeInterface $dateEnvoie): static
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(bool $supprime): static
    {
        $this->supprime = $supprime;

        return $this;
    }

    /**
     * @return Collection<int, Courrier>
     */
    public function getCourrier(): Collection
    {
        return $this->courrier;
    }

    public function addCourrier(Courrier $courrier): static
    {
        if (!$this->courrier->contains($courrier)) {
            $this->courrier->add($courrier);
            $courrier->setHistoriqueCourrier($this);
        }

        return $this;
    }

    public function removeCourrier(Courrier $courrier): static
    {
        if ($this->courrier->removeElement($courrier)) {
            // set the owning side to null (unless already changed)
            if ($courrier->getHistoriqueCourrier() === $this) {
                $courrier->setHistoriqueCourrier(null);
            }
        }

        return $this;
    }


}
