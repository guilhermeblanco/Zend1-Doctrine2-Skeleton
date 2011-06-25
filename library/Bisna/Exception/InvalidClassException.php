<?php

namespace Bisna\Exception;

/**
 * InvalidClassException class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class InvalidClassException extends \LogicException
{
    public static function missingInterfaceImplementation($className, $interfaceName)
    {
        return new self(
            'Loader "' . $className . '" class does not implement "' . $interfaceName . '" interface.'
        );
    }
}