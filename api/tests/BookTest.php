<?php

namespace App\Tests;

use App\Entity\Book;

class BookTest extends BaseTest
{
    /**
     * Retrieves the book list.
     */
    public function testRetrieveTheBookList(): void
    {
        $response = $this->request('GET', '/books');
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('hydra:totalItems', $json);
        $this->assertEquals(10, $json['hydra:totalItems']);

        $this->assertArrayHasKey('hydra:member', $json);
        $this->assertCount(10, $json['hydra:member']);
    }

    /**
     * Throws errors when data are invalid.
     */
    public function testThrowErrorsWhenDataAreInvalid(): void
    {
        $data = [
            'isbn' => '1312',
            'title' => '',
            'author' => 'Kévin Dunglas',
            'description' => 'This book is designed for PHP developers and architects who want to modernize their skills through better understanding of Persistence and ORM.',
            'publicationDate' => '2013-12-01',
        ];

        $response = $this->request('POST', '/books', $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('violations', $json);
        $this->assertCount(2, $json['violations']);

        $this->assertArrayHasKey('propertyPath', $json['violations'][0]);
        $this->assertEquals('isbn', $json['violations'][0]['propertyPath']);

        $this->assertArrayHasKey('propertyPath', $json['violations'][1]);
        $this->assertEquals('title', $json['violations'][1]['propertyPath']);
    }

    /**
     * Creates a book.
     */
    public function testCreateABook(): void
    {
        $data = [
            'isbn' => '9781782164104',
            'title' => 'Persistence in PHP with Doctrine ORM',
            'description' => 'This book is designed for PHP developers and architects who want to modernize their skills through better understanding of Persistence and ORM. You\'ll learn through explanations and code samples, all tied to the full development of a web application.',
            'author' => 'Kévin Dunglas',
            'publicationDate' => '2013-12-01',
            'abstract' => '2013-12-01',
        ];

        $response = $this->request('POST', '/books', $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('isbn', $json);
        $this->assertEquals('9781782164104', $json['isbn']);
    }

    /**
     * Updates a book.
     */
    public function testUpdateABook(): void
    {
        $data = [
            'isbn' => '9781234567897',
        ];

        $response = $this->request('PUT', $this->findOneIriBy(Book::class, ['isbn' => '9790456981541']), $data);
        $json = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));

        $this->assertArrayHasKey('isbn', $json);
        $this->assertEquals('9781234567897', $json['isbn']);
    }

    /**
     * Deletes a book.
     */
    public function testDeleteABook(): void
    {
        $response = $this->request('DELETE', $this->findOneIriBy(Book::class, ['isbn' => '9790456981541']));

        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEmpty($response->getContent());
    }
}
