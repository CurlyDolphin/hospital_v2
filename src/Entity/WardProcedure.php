<?php

namespace App\Entity;

use App\Repository\WardProcedureRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WardProcedureRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
#[ORM\UniqueConstraint(name: 'unique_ward_procedure', columns: ['ward_id', 'procedure_id'])]
#[ORM\UniqueConstraint(name: 'unique_sequence_in_ward', columns: ['ward_id', 'sequence'])]
class WardProcedure
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Ward::class, inversedBy: 'wardProcedures')]
    #[ORM\JoinColumn(nullable: false)]
    private Ward $ward;

    #[ORM\ManyToOne(targetEntity: Procedure::class, inversedBy: 'wardProcedures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ward_procedure:read'])]
    private Procedure $procedure;

    #[ORM\Column(type: 'integer')]
    #[Groups(['ward_procedure:read'])]
    private int $sequence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): static
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getWard(): Ward
    {
        return $this->ward;
    }

    public function setWard(Ward $ward): self
    {
        $this->ward = $ward;
        return $this;
    }

    public function getProcedure(): Procedure
    {
        return $this->procedure;
    }

    public function setProcedure(Procedure $procedure): self
    {
        $this->procedure = $procedure;
        return $this;
    }
}

