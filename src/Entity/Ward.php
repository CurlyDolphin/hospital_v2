<?php

namespace App\Entity;

use App\Repository\WardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WardRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
#[ORM\UniqueConstraint(columns: ['ward_number'])]
class Ward
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer", unique: true)]
    #[Groups(['ward:read', 'patient:read'])]
    private int $wardNumber;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['ward:read'])]
    private string $description;

    #[ORM\OneToMany(targetEntity: Hospitalization::class, mappedBy: 'ward')]
    private Collection $hospitalizations;

    #[ORM\OneToMany(targetEntity: WardProcedure::class, mappedBy: 'ward')]
    private Collection $wardProcedures;

    public function __construct()
    {
        $this->hospitalizations = new ArrayCollection();
        $this->wardProcedures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWardNumber(): int
    {
        return $this->wardNumber;
    }

    public function setWardNumber(int $wardNumber): self
    {
        $this->wardNumber = $wardNumber;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHospitalizations(): Collection
    {
        return $this->hospitalizations;
    }

    public function addHospitalization(Hospitalization $hospitalization): self
    {
        if (!$this->hospitalizations->contains($hospitalization)) {
            $this->hospitalizations[] = $hospitalization;
            $hospitalization->setWard($this);
        }
        return $this;
    }

    public function removeHospitalization(Hospitalization $hospitalization): self
    {
        if ($this->hospitalizations->removeElement($hospitalization)) {
            if ($hospitalization->getWard() === $this) {
                $hospitalization->setWard(null);
            }
        }
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
            $wardProcedure->setWard($this);
        }
        return $this;
    }

    public function removeWardProcedure(WardProcedure $wardProcedure): self
    {
        if ($this->wardProcedures->removeElement($wardProcedure)) {
            if ($wardProcedure->getWard() === $this) {
                $wardProcedure->setWard(null);
            }
        }
        return $this;
    }
}