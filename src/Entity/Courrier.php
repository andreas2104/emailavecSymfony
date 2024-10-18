<?php

namespace App\Entity;

use App\Repository\CourrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourrierRepository::class)]
class Courrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_envoi = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_reception = null;

    // Renommer numero_suivi en piece_jointe
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $piece_jointe = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $expediteur = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'courriersRecus')]
    private Collection $destinataire;

    public function __construct()
    {
        $this->destinataire = new ArrayCollection();
    }

    // Getter et Setter pour piece_jointe
    public function getPieceJointe(): ?string
    {
        return $this->piece_jointe;
    }

    public function setPieceJointe(?string $piece_jointe): static
    {
        $this->piece_jointe = $piece_jointe;

        return $this;
    }

    // Getter et Setter pour le reste des propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->date_envoi;
    }

    public function setDateEnvoi(\DateTimeInterface $date_envoi): static
    {
        $this->date_envoi = $date_envoi;

        return $this;
    }

    public function getDateReception(): ?\DateTimeInterface
    {
        return $this->date_reception;
    }

    public function setDateReception(?\DateTimeInterface $date_reception): static
    {
        $this->date_reception = $date_reception;

        return $this;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getExpediteur(): ?User
    {
        return $this->expediteur;
    }

    public function setExpediteur(?User $expediteur): static
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDestinataire(): Collection
    {
        return $this->destinataire;
    }

    public function addDestinataire(User $destinataire): static
    {
        if (!$this->destinataire->contains($destinataire)) {
            $this->destinataire->add($destinataire);
        }

        return $this;
    }

    public function removeDestinataire(User $destinataire): static
    {
        $this->destinataire->removeElement($destinataire);

        return $this;
    }
}
