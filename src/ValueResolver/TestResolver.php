<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Model\ComplexObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TestResolver implements ValueResolverInterface
{
    public static ?ComplexObject $complexObject = null;

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // get the argument type (e.g. BookingId)
        $argumentType = $argument->getType();

        if ($argumentType === ComplexObject::class) {
            return array_filter([self::$complexObject]);
        }

        return [];
    }
}
