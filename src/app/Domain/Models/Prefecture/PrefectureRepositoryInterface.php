<?php

namespace App\Domain\Models\Prefecture;

interface PrefectureRepositoryInterface
{
    public function save(Prefecture $prefecture);

    public function findById(PrefectureId $id): ?Prefecture;

    public function findAll(): array;

    public function findByName(?string $name): ?Prefecture;
}
