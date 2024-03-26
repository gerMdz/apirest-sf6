<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LibroControllerTest extends WebTestCase
{
    public function testVerLibro()
    {
            $cliente = static::createClient();

            $cliente->request('POST', '/api/libros');

            $this->assertEquals(200, $cliente->getResponse()->getStatusCode());
    }

    public function testSuccess()
    {
        $cliente = static::createClient();



        $cliente->request(
            'POST',
            '/api/libros',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"title": "Palabras Radiantes"}'
        );

        $this->assertEquals(200, $cliente->getResponse()->getStatusCode());
    }
}