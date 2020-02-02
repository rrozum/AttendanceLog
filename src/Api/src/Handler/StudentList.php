<?php
declare(strict_types=1);

namespace Api\Handler;


use App\Entity\Student;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class StudentList implements RequestHandlerInterface
{
    /** @var ResourceGenerator $generator */
    protected $generator;
    /** @var HalResponseFactory $halResponseFactory */
    protected $halResponseFactory;

    public function __construct(
        ResourceGenerator $generator,
        HalResponseFactory $halResponseFactory
    ) {
        $this->generator = $generator;
        $this->halResponseFactory = $halResponseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $students = Student::query()->get()->toArray();
        $studentsResource = $this->generator->fromArray(['list' => $students]);
        $resource = $this->generator->fromArray(['status' => 'ok'])
            ->embed('students', $studentsResource);

        return $this->halResponseFactory->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
