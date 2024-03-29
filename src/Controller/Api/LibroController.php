<?php

namespace App\Controller\Api;

//use App\Model\Book\BookRepositoryCriteria;
//use App\Repository\BookRepository;
//use App\Service\Book\DeleteBook;
//use App\Service\Book\GetBook;
//use App\Service\Book\BookFormProcessor;
//use App\Service\Utils\Security;
use App\Entity\Libro;
use App\Form\Model\LibroDto;
use App\Form\Type\LibroFormType;
use App\Repository\LibroRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Delete, Get, Patch, Post, Put};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use FOS\RestBundle\View\View;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LibroController extends AbstractFOSRestController
{
    #[Get(path: "/libros")]
    #[ViewAttribute(serializerGroups: ['libro'], serializerEnableMaxDepthChecks: true)]
    public function getAction(
        LibroRepository $bookRepository,
        Request         $request,
//        Security       $security
    )
    {
//        $authorId = $request->query->get('authorId');
//        $categoryId = $request->query->get('categoryId');
//        $searchText = $request->query->get('searchText');
//        $page = $request->query->get('page');
//        $itemsPerPage = $request->query->get('itemsPerPage');
//        $criteria = new BookRepositoryCriteria(
//            $authorId,
//            $categoryId,
//            $searchText,
//            $itemsPerPage !== null ? \intval($itemsPerPage) : 10,
//            $page !== null ? \intval($page) : 1,
//            $security->getCurrentUser()->getId()
//        );
//        return $bookRepository->findByCriteria($criteria);

        return $bookRepository->findAll();
    }

    #[Get(path: "/libros/{id}")]
    #[ViewAttribute(serializerGroups: ['libro'], serializerEnableMaxDepthChecks: true)]
    public function getSingleAction(string $id, GetBook $getBook)
    {
        try {
            $book = ($getBook)($id);
        } catch (Exception) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return $book;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param FilesystemOperator $defaultStorage
     * @return FormInterface|Libro
     * @throws FilesystemException
     */
    #[Post(path: "/libros")]
    #[ViewAttribute(serializerGroups: ['libro'], serializerEnableMaxDepthChecks: true)]
    public function postAction(
        Request                $request,
        EntityManagerInterface $em,
        FileUploader           $fileUploader
    ): FormInterface|Libro
    {
        $libroDto = new LibroDto();
        $form = $this->createForm(LibroFormType::class, $libroDto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $libro = new Libro();
            $libro->setTitle($libroDto->title);

            if ($libroDto->base64Image) {
                $fileName = $fileUploader->uploadBase64File($libroDto->base64Image);
                $libro->setImage($fileName);
            }

            $em->persist($libro);
            $em->flush();
            return $libro;
        }

//        [$book, $error] = ($bookFormProcessor)($request);
//        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
//        $data = $book ?? $error;
//        return View::create($data, $statusCode);

        return $form;
    }

    #[Put(path: "/libros/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function editAction(
        string            $id,
        BookFormProcessor $bookFormProcessor,
        Request           $request
    )
    {
        try {
            [$book, $error] = ($bookFormProcessor)($request, $id);
            $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $book ?? $error;
            return View::create($data, $statusCode);
        } catch (Throwable $e) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Patch(path: "/libros/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function patchAction(
        string  $id,
        GetBook $getBook,
        Request $request
    )
    {
        $book = ($getBook)($id);
        $data = json_decode($request->getContent(), true);
        $book->patch($data);
        return View::create($book, Response::HTTP_OK);
    }

    #[Delete(path: "/libros/{id}")]
    #[ViewAttribute(serializerGroups: ['book'], serializerEnableMaxDepthChecks: true)]
    public function deleteAction(string $id, DeleteBook $deleteBook)
    {
        try {
            ($deleteBook)($id);
        } catch (Throwable) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}