<?php
declare(strict_types=1);

namespace App\Error;

use Fig\Http\Message\StatusCodeInterface;
use Zend\ProblemDetails\Exception\ProblemDetailsExceptionInterface;

interface ProblemJsonErrorInterface extends ProblemDetailsExceptionInterface, StatusCodeInterface
{
}
