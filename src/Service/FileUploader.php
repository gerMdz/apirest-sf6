<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;

class FileUploader
{
    public function __construct(private FilesystemOperator $defaultStorage)
    {
    }

    public function uploadBase64File(string $base64File):string
    {
        $extension = explode('/', mime_content_type($base64File))[1];

        $data = explode(',', $base64File);
        $fileName = sprintf('%s.%s', uniqid('image_', true),$extension);

        $this->defaultStorage->write($fileName, base64_decode($data[1]));

        return  $fileName;

    }
}