<?php

namespace CreativeNotes\Infrastructure\Seeds;

use CreativeNotes\Domain\Entity\Note;
use Yggdrasil\Utils\Seeds\AbstractSeeds;

/**
 * Class NoteSeeds
 *
 * @package CreativeNotes\Infrastructure\Seeds
 */
class NoteSeeds extends AbstractSeeds
{
    /**
     * Creates user seeds
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function create(): array
    {
        return [
            (new Note())
                ->setTitle('The Dependency Rule')
                ->setContent('The overriding rule that makes this architecture work is The Dependency Rule. This rule says that source code dependencies can only point inwards. Nothing in an inner circle can know anything at all about something in an outer circle. In particular, the name of something declared in an outer circle must not be mentioned by the code in the an inner circle. That includes, functions, classes. variables, or any other named software entity.'),
            (new Note())
                ->setTitle('Frameworks and Drivers')
                ->setContent('The outermost layer is generally composed of frameworks and tools such as the Database, the Web Framework, etc. Generally you don’t write much code in this layer other than glue code that communicates to the next circle inwards.'),
            (new Note())
                ->setTitle('Crossing boundaries')
                ->setContent('We usually resolve this apparent contradiction by using the Dependency Inversion Principle. In a language like Java, for example, we would arrange interfaces and inheritance relationships such that the source code dependencies oppose the flow of control at just the right points across the boundary.')
        ];
    }
}
