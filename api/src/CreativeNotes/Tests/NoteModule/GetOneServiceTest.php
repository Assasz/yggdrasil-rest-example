<?php

namespace CreativeNotes\Tests\NoteModule;

use CreativeNotes\Application\Service\NoteModule\GetOneService;
use CreativeNotes\Application\Service\NoteModule\Request\GetOneRequest;
use CreativeNotes\Domain\Entity\Note;
use CreativeNotes\Infrastructure\Configuration\Test\TestConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class GetOneServiceTest
 *
 * @package CreativeNotes\Tests\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetOneServiceTest extends TestCase
{
    /**
     * @var GetOneService
     */
    private $service;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $this->service = new GetOneService(new TestConfiguration());
    }

    /**
     * @covers GetOneService::process()
     */
    public function testReturnNoteOnExistentNoteId(): void
    {
        $request = (new GetOneRequest())->setNoteId(1);
        $response = $this->service->process($request);

        $this->assertInstanceOf(Note::class, $response->getNote());
    }

    /**
     * @covers GetOneService::process()
     */
    public function testFailOnNonExistentNoteId(): void
    {
        $request = (new GetOneRequest())->setNoteId(9999);
        $response = $this->service->process($request);

        $this->assertFalse($response->isSuccess());
    }
}
