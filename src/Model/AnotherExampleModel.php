<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints\Cascade;
use Symfony\Component\Validator\Constraints\NotNull;

#[Cascade]
class AnotherExampleModel
{
    public function __construct(
        #[NotNull(groups: ['Default', 'constraint64', 'constraint65', 'constraint66'])]
        private readonly mixed $property = null,
        private readonly ?AnotherExampleModel $anotherProperty = null,
    ) {
    }

    public function getProperty(): mixed
    {
        return $this->property;
    }

    public function getAnotherProperty(): ?AnotherExampleModel
    {
        return $this->anotherProperty;
    }
}
