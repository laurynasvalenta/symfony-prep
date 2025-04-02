<?php

declare(strict_types=1);

namespace App\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CustomResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getName() !== 'abSum') {
            return [];
        }

        return [$request->query->getInt('a') + $request->query->getInt('b')];
    }
}
