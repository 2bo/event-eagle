<?php

namespace App\Domain\Models\Prefecture;

class PrefectureId
{
    private $value;

    public function __construct(int $value)
    {
        if ($value < 1 || $value > 47) {
            throw new \DomainException("Prefecture id must be between 1 and 47");
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}
