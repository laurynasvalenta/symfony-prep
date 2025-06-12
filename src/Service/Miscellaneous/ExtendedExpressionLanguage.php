<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExtendedExpressionLanguage extends ExpressionLanguage
{
    public function __construct()
    {
        parent::__construct();

        $this->register(
            'random_url',
            fn(string $domain) => sprintf('random_url(%s)', $domain),
            function (array $variables, string $domain): string {
                return sprintf('https://%s/%s', $domain, md5(random_bytes(16)));
            }
        );
    }
}
