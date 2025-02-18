<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\GenderEnum;
use App\Repository\PatientRepository;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
class Patient
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['patient:read'])]
    private int $id;

    #[ORM\Column(type: "string", length: 80)]
    #[Assert\Length(min: 1, max: 80)]
    #[Groups(['patient:read'])]
    private string $name;

    #[ORM\Column(type: "string", length: 80)]
    #[Assert\Length(min: 1, max: 80)]
    #[Groups(['patient:read'])]
    private string $lastName;

    #[ORM\Column(type: "string", length: 6, enumType: GenderEnum::class)]
    #[Groups(['patient:read'])]
    private GenderEnum $gender;

    #[ORM\Column(type: "boolean")]
    #[Groups(['patient:read'])]
    private bool $isIdentified = false;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(['patient:read'])]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(type: "integer", unique: true)]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "card_number_seq", allocationSize: 1)]
    #[Groups(['patient:read'])]
    private ?int $cardNumber = null;

    #[ORM\OneToMany(targetEntity: Hospitalization::class, mappedBy: 'patient')]
    #[Groups(['patient:read'])]
    private Collection $hospitalizations;

    public function __construct()
    {
        $this->hospitalizations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): GenderEnum
    {
        return $this->gender;
    }

    public function setGender(GenderEnum $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function isIdentified(): bool
    {
        return $this->isIdentified;
    }

    public function setIdentified(bool $isIdentified): self
    {
        $this->isIdentified = $isIdentified;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }

    public function getCardNumber(): int
    {
        return $this->cardNumber;
    }

    public function setCardNumber(int $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

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
            $hospitalization->setPatient($this);
        }
        return $this;
    }
}
