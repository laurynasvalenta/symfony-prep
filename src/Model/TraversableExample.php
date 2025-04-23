<?php

declare(strict_types=1);

namespace App\Model;

use ArrayIterator;
use Symfony\Component\Validator\Constraints\Traverse;

#[Traverse]
class TraversableExample extends ArrayIterator
{
}
