<?php

declare(strict_types=1);

namespace App\Controller\Miscellaneous;

use Exception;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class CustomErrorController
{
    #[Route('/topic13/error')]
    public function triggerError(): Response
    {
        throw new Exception('Demo exception for Topic13 error handling');
    }
}
