<?php

namespace CreativeNotes\Tests\NoteModule;

use CreativeNotes\Application\Service\NoteModule\CreateService;
use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Domain\Entity\Note;
use CreativeNotes\Infrastructure\Configuration\Test\TestConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class CreateServiceTest
 *
 * @package CreativeNotes\Tests\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class CreateServiceTest extends TestCase
{
    /**
     * @var CreateService
     */
    private $service;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $this->service = new CreateService(new TestConfiguration());
    }

    public function testFailureOnInvalidNoteTitle(): void
    {
        $request = (new CreateRequest())
            ->setTitle('')
            ->setContent('foo');

        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());

        $request->setTitle('yNvvIqWETldGdA6KbqJ5ZnZYlDCkVFor8ysVRqymt2QDY3IavF0FRxRkWE9k0SNdTyqPz14qRLTS6TOxdQS0uP4RG8pD4i7QKdPzaBUb8aPnJWj7rxNNzQp8K1kQEDfhHfjjBBJRQirE896oK3ckqNIOK6HGI7mg2DLGZ1Y7zfPEXEtuxMZ2hyrjV91lyLpNCoJtj1CO4oKFvTYZk7pFaOY931tOv2YUfwHj1AA1cD8EjQp1PgpSRbPYqD17arMQ');
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    public function testFailureOnInvalidNoteContent(): void
    {
        $request = (new CreateRequest())
            ->setTitle('foo')
            ->setContent('');

        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());

        $request->setContent('OxW8CsxjUGXjg6mtojzwOLOHPqnZ0o32rLq3UbHZBwspKp73d2TQVucmIgOzE6AJbIH5qsD2L9A4a5tkoWYbIlrta2WINpkVOtni3VIvfDPxYNaDQPKYsqtrvC8wNOmibhjgayA16IPjSiaVZVY00gANVWgITdapYR0iuVb5aERFXcAW6ttndriZdwKT4dy4XcOjOck4juxWiIyoeTD06xYMiBYftWsh9H0gWFL0gmlkpkE0hIt0DPd4d15gzZtW8DeTLg7fm31g0Vjieq5o4U0vh5f8K5GKYPV4V5p9zlNIZf5dUAgCvte4cBjNdqZIT8skAQGRDmQItvtiFUVl2pwg0Um08eA1M1P9UeMh31RSy0bzyYbpWyMkCFVSKnJlHFUO7WHq8ur907nsO0ucQscvC0If21DD4TClzsGnspWGaYHUdeoHxCPWOfLQwrcOlEmCrPpKqJGwrjCQYg5xKMGXh1AAYnk2ClRtbCdBp0uXFjSzVpvAJvszDoDQUorw2bMsiA9nuhb7GJmwxDiXHNPYMQdiXAWqTnBbbgLzKDU82hTpd61Jm2LErDZPhJiw9MUpHVlkejo2z27jjndJtXRaa6RPjeLYWfOaZKlsDaBZCNcCQxZZ8cIZYBe9sB2gUJEdQV4Y2rlcmxDgzuBXY2FndYJeRlkSyHx7FilfTxm5J4e20LwsrcS0BpeQQV4XTh0X1Horla956r79JEFFIDXwcQCcvULK2nsesG4sDBgpCbSGFHegLoRSUTpdDXmjKjiHxJ8R1RqL8kAlIvpBg73yQiu2GBB2KLfiwj8xC1nViLIIqe6M9cehvEQGP4AJfHOsqFF38oN4FvzljdyrwsWaGw3EoFD7vFuWbVQ3MM4ZJPptVJs5H4NexVlb4CckMh2s6iZrPD9jdXVoPNTr4jDJJIKGvw0sL7odVjdzNVb5qdHpjLH90jbq5fWQZhuEMeTGfuZOMifdEikjJolZ56njXeL1cYuErk9UKDOaI');
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    public function testReturnNoteOnSuccess(): void
    {
        $request = (new CreateRequest())
            ->setTitle('foo')
            ->setContent('bar');

        $response = $this->service->process($request);

        $this->assertInstanceOf(Note::class, $response->getNote());
    }
}
