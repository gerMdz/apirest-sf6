<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Repository\LibroRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/libro', name: 'app_library_listado')]
    public function list(Request $request, LibroRepository $libroRepository): JsonResponse
    {

        $this->logger->info('Listando cosas');
        $libros = $libroRepository->findAll();
        $response = new JsonResponse();
        $arrayLibros = [];

        foreach ($libros as $libro) {
            $arrayLibros[] = [
                'id' => $libro->getId(),
                'title' => $libro->getTitle(),
                'image' => $libro->getImage(),
            ];
        }

        $response->setData(
            [
                'success' => true,
                'data' => $arrayLibros
            ]
        );


        return $response;

    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/libro/crear', name: 'app_libro_crear')]
    public function crearLibro(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $libro = new Libro();
        $response = new JsonResponse();

        $title = $request->get('title', null);

        if (empty($title)) {
            $response->setData([
                    'success' => false,
                    'error' => 'El título no puede se nulo',
                    'data' => null
                ]
            );
            return $response;
        }
        $libro->setTitle($title)
            ->setImage('');

        $entityManager->persist($libro);
        $entityManager->flush();


        $response->setData([
                'success' => true,
                'data' => [
                    [
                        'id' => $libro->getId(),
                        'title' => $libro->getTitle()

                    ],
                ]
            ]
        );

        return $response;

    }
}
