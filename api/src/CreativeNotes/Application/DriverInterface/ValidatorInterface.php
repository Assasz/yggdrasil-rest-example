<?php

namespace CreativeNotes\Application\DriverInterface;

/**
 * Interface ValidatorInterface
 *
 * @package CreativeNotes\Application\DriverInterface
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
interface ValidatorInterface
{
    /**
     * Returns true if given entity object is valid, false otherwise
     *
     * @param object $entity
     * @return bool
     */
    public function isValid(object $entity): bool;
}
