<?php

namespace App\Tests;

use App\Entity\Greeting;

class GreetingTest extends BaseTest
{
    /**
     * Retrieves the book list.
     */
    public function testRetrieveTheBookList(): void
    {
        $response = $this->request('GET', '/greetings');
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('hydra:totalItems', $json);
        $this->assertEquals(11, $json['hydra:totalItems']);

        $this->assertArrayHasKey('hydra:member', $json);
        $this->assertCount(11, $json['hydra:member']);
    }


    /**
     * Creates a greeting.
     */
    public function testCreateAGreeting(): void
    {
        $data = [
            'name' => 'Persistence in PHP with Doctrine ORM',
        ];

        $response = $this->request('POST', '/greetings', $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('name', $json);
        $this->assertEquals('Persistence in PHP with Doctrine ORM', $json['name']);
    }

    /**
     * Updates a greeting.
     */
    public function testUpdateAGreeting(): void
    {
        $data = [
            'name' => 'updated title',
        ];

        $response = $this->request('PUT', $this->findOneIriBy(Greeting::class, ['name' => 'test']), $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('name', $json);
        $this->assertEquals('updated title', $json['name']);
    }

    /**
     * Deletes a book.
     */
    public function testDeleteAGreeting(): void
    {
        $response = $this->request('DELETE', $this->findOneIriBy(Greeting::class, ['name' => 'test']));

        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEmpty($response->getContent());
    }
}
