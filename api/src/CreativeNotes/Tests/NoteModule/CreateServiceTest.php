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

    /**
     * @covers CreateService::process()
     */
    public function testFailOnInvalidNoteTitle(): void
    {
        $request = (new CreateRequest())
            ->setTitle('')
            ->setContent('foo');

        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());

        $request->setTitle($this->generateRandomString(256));
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    /**
     * @covers CreateService::process()
     */
    public function testFailOnInvalidNoteContent(): void
    {
        $request = (new CreateRequest())
            ->setTitle('foo')
            ->setContent('');

        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());

        $request->setContent($this->generateRandomString(1001));
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    /**
     * @covers CreateService::process()
     */
    public function testReturnNoteOnSuccess(): void
    {
        $request = (new CreateRequest())
            ->setTitle('foo')
            ->setContent('bar');

        $response = $this->service->process($request);

        $this->assertInstanceOf(Note::class, $response->getNote());
    }

    /**
     * @param int $length
     * @return string
     */
    private function generateRandomString(int $length): string
    {
        return substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyz', ceil($length/strlen($x)))),1, $length);
    }
}
