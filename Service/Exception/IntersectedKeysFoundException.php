<?php

namespace Itamit\ClassifierErrorBundle\Service\Exception;

use RuntimeException;

/**
 * Error: the key in the provider errors matches the key in the general registry
 */
class IntersectedKeysFoundException extends RuntimeException
{

}
