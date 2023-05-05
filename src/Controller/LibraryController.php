<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/listado', name: 'app_library_listado')]
    public function list(): JsonResponse
    {
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
                    ]
                ]
            ]
        );

        return $response;

    }
}
