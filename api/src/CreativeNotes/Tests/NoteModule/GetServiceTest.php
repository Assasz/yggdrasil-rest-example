<?php

namespace CreativeNotes\Tests\NoteModule;

use CreativeNotes\Application\Service\NoteModule\GetService;
use CreativeNotes\Application\Service\NoteModule\Request\GetRequest;
use CreativeNotes\Domain\Entity\Note;
use CreativeNotes\Infrastructure\Configuration\Test\TestConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Class GetServiceTest
 *
 * @package CreativeNotes\Tests\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class GetServiceTest extends TestCase
{
    /**
     * @var GetService
     */
    private $service;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function setUp(): void
    {
        $this->service = new GetService(new TestConfiguration());
    }

    /**
     * @covers GetService::process()
     */
    public function testReturnNotes(): void
    {
        $request = new GetRequest();
        $response = $this->service->process($request);

        $this->assertIsArray($response->getNotes());

        if (!empty($response->getNotes())) {
            $this->assertInstanceOf(Note::class, $response->getNotes()[0]);
        }
    }

    /**
     * @covers GetService::process()
     */
    public function testReturnNotesOnNonEmptySearchTerm(): void
    {
        $request = (new GetRequest())->setSearchTerm('foo');
        $response = $this->service->process($request);

        $this->assertIsArray($response->getNotes());

        if (!empty($response->getNotes())) {
            $note = $response->getNotes()[0];

            $this->assertInstanceOf(Note::class, $note);
            $this->assertContains('foo', [$note->getTitle(), $note->getContent()]);
        }
    }
}
