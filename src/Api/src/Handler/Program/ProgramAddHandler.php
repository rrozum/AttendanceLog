<?php
declare(strict_types=1);

namespace Api\Handler\Program;


use Api\Handler\Exception\WrongParamsException;
use Fig\Http\Message\StatusCodeInterface;
use http\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class ProgramAddHandler implements RequestHandlerInterface
{
    protected $generator;
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
        $parsedBody = $request->getParsedBody();
        $name = $parsedBody['name'] ?? null;
        $department = $parsedBody['department'] ?? null;

        if (empty($name)) {
            throw WrongParamsException::create('name');
        }

        $resource = $this->generator->fromArray(['programs' => 'ok']);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
