<?php

namespace App\Entity;

use App\Repository\HospitalizationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HospitalizationRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
class Hospitalization
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'hospitalizations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['hospitalization:read', 'procedure:read'])]
    private Patient $patient;

    #[ORM\ManyToOne(targetEntity: Ward::class, inversedBy: 'hospitalizations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['hospitalization:read', 'patient:read'])]
    private Ward $ward;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Expression(
        "value === null or value >= this.getCreatedAt()",
        message: "Дата выписки не может быть раньше даты поступления"
    )]
    #[Groups(['patient:read'])]
    private ?\DateTimeInterface $dischargeDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): static
    {
        $this->patient = $patient;
        return $this;
    }

    public function getWard(): Ward
    {
        return $this->ward;
    }

    public function setWard(Ward $ward): static
    {
        $this->ward = $ward;
        return $this;
    }

    public function getDischargeDate(): ?\DateTimeInterface
    {
        return $this->dischargeDate;
    }

    public function setDischargeDate(?\DateTimeInterface $dischargeDate): self
    {
        $this->dischargeDate = $dischargeDate;
        return $this;
    }
}
