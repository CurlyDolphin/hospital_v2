<?php

namespace App\Dto\Procedure;

class ProcedureInfoDto
{
    public int $id;
    public string $name;
    public string $description;
    /** @var string[] */
    public array $wards = [];
    /** @var string[] */
    public array $patients = [];

    public function __construct(int $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function addWard(string $wardName): void
    {
        $this->wards[] = $wardName;
    }

    public function addPatient(string $patientName): void
    {
        $this->patients[] = $patientName;
    }
}
