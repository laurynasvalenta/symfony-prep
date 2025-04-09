<?php

declare(strict_types=1);

namespace App\Controller\TemplatingWithTwig;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TemplatingWithTwigController extends AbstractController
{
    public static string $valueToOutput = '';


    #[Route('/topic5/no-escaping1'), Template('topic5/template1.html.twig')]
    public function template1(): array
    {
        return ['content' => '<b>Bolded text</b>'];
    }

    #[Route('/topic5/no-escaping2'), Template('topic5/template2.html.twig')]
    public function template2(): array
    {
        return [
            'content1' => '<b>Bolded text1</b>',
            'content2' => '<b>Bolded text2</b>'
        ];
    }

    #[Route('/topic5/escaping-strategy-change1'), Template('topic5/template3.html.twig')]
    public function template3(): array
    {
        return [
            'content' => "TEST'"
        ];
    }

    #[Route('/topic5/escaping-strategy-change2'), Template('topic5/template4.html.twig')]
    public function template4(): array
    {
        return [
            'content' => "TEST'"
        ];
    }

    #[Route('/topic5/escaping-strategy-change3'), Template('topic5/template5.js.twig')]
    public function template5(): array
    {
        return [
            'content' => "TEST'"
        ];
    }

    #[Route('/topic5/template-inheritance1'), Template('topic5/template6.html.twig')]
    public function template6(): array
    {
        return [];
    }

    #[Route('/topic5/template-inheritance2'), Template('topic5/template7.html.twig')]
    public function template7(): array
    {
        return [];
    }

    #[Route('/topic5/template-include'), Template('topic5/template8.html.twig')]
    public function template8(): array
    {
        return [];
    }

    #[Route('/topic5/controller-rendering'), Template('topic5/template9.html.twig')]
    public function template9(): array
    {
        return [];
    }

    #[Route('/topic5/controller-rendering-target')]
    public function controllerRenderingTarget(): Response
    {
        return new Response(self::$valueToOutput);
    }

    #[Route('/topic5/list-of-countries'), Template('topic5/template10.html.twig')]
    public function template10(): array
    {
        return [
            'countries' => [
                'New Zealand',
                'Finland',
                'Indonesia',
                'Jordan',
                'Ireland',
                'South Korea'
            ]
        ];
    }

    #[Route('/topic5/global-variables'), Template('topic5/template11.html.twig')]
    public function template11(): array
    {
        return [];
    }

    #[Route('/topic5/twig-filter'), Template('topic5/template12.html.twig')]
    public function template12(): array
    {
        return [];
    }

    #[Route('/topic5/interpolation'), Template('topic5/template13.html.twig')]
    public function template13(Request $request): array
    {
        return ['request' => $request];
    }

    #[Route('/topic5/asset'), Template('topic5/template14.html.twig')]
    public function template14(): array
    {
        return [];
    }
}
