<?php

namespace App\Form\Model;

use App\Entity\Libro;
use Symfony\Component\Validator\Constraints\Collection;

class LibroDto
{
    public string|null $title = null;
    public string|null $base64Image = null;
    public array|null $categories = null;
    public function __construct()
    {
        $this->categories = [];
    }

    public static function crearDesdeLibro(Libro $libro):self
    {
        $dto = new self();

        $dto->title = $libro->getTitle();
        $dto->base64Image = $libro->getImage();
//        foreach ($libro->getCategories() as $category) {
//            $dto->categories[] = $category;
//        }
        return $dto;


    }

}
