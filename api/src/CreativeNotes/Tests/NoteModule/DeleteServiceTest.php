<?php

namespace CreativeNotes\Tests\NoteModule;

use CreativeNotes\Application\Service\NoteModule\DeleteService;
use CreativeNotes\Application\Service\NoteModule\Request\DeleteRequest;
use CreativeNotes\Infrastructure\Configuration\Test\TestConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class DeleteServiceTest
 *
 * @package CreativeNotes\Tests\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class DeleteServiceTest extends TestCase
{
    /**
     * @var DeleteService
     */
    private $service;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $this->service = new DeleteService(new TestConfiguration());
    }

    /**
     * @covers DeleteService::process()
     */
    public function testFailOnNonExistentNoteId(): void
    {
        $request = (new DeleteRequest())->setNoteId(9999);
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }

    /**
     * @covers DeleteService::process()
     */
    public function testSuccessOnExistentNoteId(): void
    {
        $newestNote = $this->service->noteRepository->fetch([], ['id' => 'desc'], 1)[0];

        $request = (new DeleteRequest())->setNoteId($newestNote->getId());
        $response = $this->service->process($request);

        $this->assertTrue($response->isSuccess());
    }
}
