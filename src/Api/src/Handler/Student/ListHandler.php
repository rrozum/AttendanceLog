<?php
declare(strict_types=1);

namespace Api\Handler\Student;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Service\Student\ListService;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;


class ListHandler implements RequestHandlerInterface
{
    protected $generator;
    protected $halResponseFactory;
    protected $studentListService;

    public function __construct(
        ResourceGenerator $generator,
        HalResponseFactory $halResponseFactory,
        ListService $studentListService
    ) {
        $this->generator = $generator;
        $this->halResponseFactory = $halResponseFactory;
        $this->studentListService = $studentListService;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $allStudents = $this->studentListService->getAllStudentsAsArray();

        $resource = $this->generator->fromArray(['students' => $allStudents]);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
