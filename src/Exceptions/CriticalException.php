<?php
declare(strict_types=1);

namespace EoneoPay\Utils\Exceptions;

use Exception;

/**
 * Exception thrown if an error which is critical and requires human intervention to resolve occurs.
 */
abstract class CriticalException extends Exception
{
    //
}
