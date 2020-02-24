<?php
declare(strict_types=1);

namespace Api\Handler\School;


use App\Collection\SchoolCollection;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class SchoolListHandler implements RequestHandlerInterface
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

        $schoolList = SchoolCollection::getSchoolList($offset, $limit);

        $resource = $this->generator->fromArray(['schools' => $schoolList]);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
