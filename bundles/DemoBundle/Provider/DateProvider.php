<?php

declare(strict_types=1);

namespace DemoBundle\Provider;

use DateTime;

class DateProvider
{
    public function getCurrentDate(): DateTime
    {
        return new DateTime();
    }
}
