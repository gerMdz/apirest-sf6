<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/listado', name: 'app_library_listado')]
    public function list(Request $request): JsonResponse
    {

        $title = $request->get('titulo', 'Los archivos de la tormenta');

        $this->logger->info('Se llama a la acción');
        $response = new JsonResponse();
        $response->setData([
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'El imperio final'

                    ],
                    [
                        'id' => 2,
                        'title' => 'El héroe de las eras'
                    ],
                    [
                        'id' => 3,
                        'title' => $title
                    ]
                ]
            ]
        );

        return $response;

    }
}
