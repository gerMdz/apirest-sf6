<?php

namespace App\Form\Model;

use App\Entity\Category;

class CategoryDto
{
    public int $id;
    public string|null $name = null;

    public static function crearDesdeCategory(Category $category): self
    {

        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();

        return $dto;

    }

}