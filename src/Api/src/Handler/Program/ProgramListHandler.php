<?php
declare(strict_types=1);

namespace Api\Handler\Program;


use App\Collection\ProgramCollection;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class ProgramListHandler implements RequestHandlerInterface
{
    const MAX_LIMIT = 500;
    const START_OFFSET = 0;

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
        $queryParams = $request->getQueryParams();

        $limit = isset($queryParams['limit'])
            ? (int)$queryParams['limit']
            : self::MAX_LIMIT;

        $offset = isset($queryParams['offset'])
            ? (int)$queryParams['offset']
            : self::START_OFFSET;

        $programList = ProgramCollection::getProgramList($offset, $limit);

        $resource = $this->generator->fromArray(['programs' => $programList]);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
