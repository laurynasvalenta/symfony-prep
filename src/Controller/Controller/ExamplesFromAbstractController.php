<?php

declare(strict_types=1);

namespace App\Controller\Controller;

use App\Form\FileUploadFormType;
use App\Form\SimpleFormType;
use App\Model\ComplexObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExamplesFromAbstractController extends AbstractController
{
    #[Route('/topic3/router')]
    public function router(): Response
    {
        return new Response($this->container->get('router')->generate('app__examplesfromabstract_router', [], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    #[Route('/topic3/router2')]
    public function router2(): Response
    {
        return new Response($this->generateUrl('app__examplesfromabstract_router2', [], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    #[Route('/topic3/main-request-checker')]
    public function mainRequestChecker(): Response
    {
        return new Response($this->container->get('request_stack')->getParentRequest() ? 'sub-request' : 'main request');
    }

    #[Route('/topic3/forward-to-main-request-checker')]
    public function forwardToMainRequestChecker(): Response
    {
        return new Response(
            $this->forward('App\Controller\Controller\ExamplesFromAbstractController::mainRequestChecker')->getContent()
        );
    }

    #[Route('/topic3/http-kernel')]
    public function httpKernel(): Response
    {
        $request = new Request();
        $request->attributes->set('_controller', 'App\Controller\Controller\ExamplesFromAbstractController::mainRequestChecker');

        return new Response(
            $this->container->get('http_kernel')->handle($request, HttpKernelInterface::SUB_REQUEST)->getContent()
        );
    }

    #[Route('/topic3/serializer')]
    public function serializer(ComplexObject $input): Response
    {
        return new Response(
            $this->container->get('serializer')->serialize($input, 'json')
        );
    }

    #[Route('/topic3/authorization')]
    public function authorization(): Response
    {
        return new Response(
            $this->isGranted('ROLE_USER') ? 'granted' : 'denied'
        );
    }

    #[Route('/topic3/protected')]
    public function protected(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new Response('');
    }

    #[Route('/topic3/twig1')]
    public function twig1(): Response
    {
        return new Response($this->container->get('twig')->render('topic3/twig1.html.twig'));
    }

    #[Route('/topic3/twig2')]
    public function twig2(): string
    {
        return $this->renderView('topic3/twig2.html.twig');
    }

    #[Route('/topic3/form')]
    public function form(Request $request): Response
    {
        $form = $this->createForm(SimpleFormType::class);

        $form->handleRequest($request);

        return $this->render('topic3/form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/topic3/token-storage')]
    public function tokenStorage(): Response
    {
        return new Response($this->container->get('security.token_storage')->getToken()?->getUserIdentifier() ?: 'Welcome, Anonymous!');
    }

    #[Route('/topic3/csrf-token')]
    public function csrfTokenCheck(Request $request): Response
    {
        if ($this->isCsrfTokenValid('token', $request->query->get('token'))) {
            return new Response('Valid token provided!');
        }

        return new Response($this->container->get('security.csrf.token_manager')->getToken('token')->getValue());
    }

    #[Route('/topic3/container-parameters')]
    public function containerParameters(): Response
    {
        return new Response($this->container->get('parameter_bag')->get('container.build_id'));
    }

    #[Route('/topic3/upload-file')]
    public function uploadFile(Request $request): Response
    {
        $form = $this->createForm(FileUploadFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file !== null) {
                try {
                    $file->move(__DIR__ . '/../../../var/uploads/', 'symfony.svg');
                } catch (FileException) {
                }
            }
        }

        return $this->render('topic3/form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/topic3/download-file')]
    public function downloadFile(): Response
    {
        $binaryFileResponse = new BinaryFileResponse(__DIR__ . '/../../../var/uploads/symfony.svg', 200, ['Content-Type' => 'image/svg+xml']);
        $binaryFileResponse->deleteFileAfterSend();

        return $binaryFileResponse;
    }

    #[Route('/topic3/custom-resolver')]
    public function customResolver(int $abSum = 0): Response
    {
        return new Response(sprintf('abSum is %d.', $abSum));
    }
}
