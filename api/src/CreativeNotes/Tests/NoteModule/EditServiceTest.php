<?php

namespace CreativeNotes\Tests\NoteModule;

use CreativeNotes\Application\Service\NoteModule\EditService;
use CreativeNotes\Application\Service\NoteModule\Request\EditRequest;
use CreativeNotes\Domain\Entity\Note;
use CreativeNotes\Infrastructure\Configuration\Test\TestConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class EditServiceTest
 *
 * @package CreativeNotes\Tests\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class EditServiceTest extends TestCase
{
    /**
     * @var EditService
     */
    private $service;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $this->service = new EditService(new TestConfiguration());
    }

    /**
     * @covers EditService::process()
     */
    public function testFailOnNonExistentNoteId(): void
    {
        $request = (new EditRequest())->setNoteId(9999);
        $response = $this->service->process($request);

        $this->assertFalse($response->isFound());
    }

    /**
     * @covers EditService::process()
     */
    public function testFailOnInvalidNoteTitle(): void
    {
        $request = (new EditRequest())
            ->setNoteId(1)
            ->setTitle('')
            ->setContent('newContent');

        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());

        $request->setTitle($this->generateRandomString(256));
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    /**
     * @covers EditService::process()
     */
    public function testFailOnInvalidNoteContent(): void
    {
        $request = (new EditRequest())
            ->setNoteId(1)
            ->setTitle('newTitle')
            ->setContent('');

        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());

        $request->setContent($this->generateRandomString(1001));
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    /**
     * @covers EditService::process()
     */
    public function testReturnNoteOnSuccess(): void
    {
        $request = (new EditRequest())
            ->setNoteId(1)
            ->setTitle('newTitle')
            ->setContent('newContent');

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
