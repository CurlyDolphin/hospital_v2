<?php

namespace App\Entity;

use App\Repository\ProcedureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProcedureRepository::class)]
class Procedure
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ward_procedure:read', 'procedure:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 120, unique: true)]
    #[Groups(['ward_procedure:read', 'procedure:read'])]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['ward_procedure:read', 'procedure:read'])]
    private string $description;

    #[ORM\OneToMany(targetEntity: WardProcedure::class, mappedBy:'procedure')]
    private Collection $wardProcedures;

    public function __construct()
    {
        $this->wardProcedures = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getWardProcedures(): Collection
    {
        return $this->wardProcedures;
    }

    public function addWardProcedure(WardProcedure $wardProcedure): self
    {
        if (!$this->wardProcedures->contains($wardProcedure)) {
            $this->wardProcedures[] = $wardProcedure;
            $wardProcedure->setProcedure($this);
        }
        return $this;
    }

    public function removeWardProcedure(WardProcedure $wardProcedure): self
    {
        if ($this->wardProcedures->removeElement($wardProcedure)) {
            if ($wardProcedure->getProcedure() === $this) {
                $wardProcedure->setProcedure(null);
            }
        }
        return $this;
    }
}
