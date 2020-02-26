<?php
declare(strict_types=1);

namespace Api\Handler\Student;

use App\Collection\StudentsCollection;
use App\Model\FilterModel;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Service\Student\ListService;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;


class StudentListHandler implements RequestHandlerInterface
{
    const MAX_LIMIT = 500;
    const START_OFFSET = 0;

    /** @var ResourceGenerator $generator */
    protected $generator;
    /** @var HalResponseFactory $halResponseFactory */
    protected $halResponseFactory;
    /** @var ListService */
    protected $listService;

    public function __construct(
        ResourceGenerator $generator,
        HalResponseFactory $halResponseFactory,
        ListService $listService
    ) {
        $this->generator = $generator;
        $this->halResponseFactory = $halResponseFactory;
        $this->listService = $listService;
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

        $programIds = isset($queryParams['program_id'])
            ? (array)$queryParams['program_id']
            : null;

        $schoolIds = isset($queryParams['school_id'])
            ? (array)$queryParams['school_id']
            : null;

        $courseIds = isset($queryParams['course_id'])
            ? (array)$queryParams['course_id']
            : null;

        $date = isset($queryParams['date'])
            ? $queryParams['date']
            : null;

        $attendance = isset($queryParams['attendance'])
            ? $queryParams['attendance']
            : null;
        $attendance = filter_var($attendance, FILTER_VALIDATE_BOOLEAN);

        $filter = new FilterModel($programIds, $courseIds, $schoolIds, $date, $attendance);

        $studentList = $this->listService->getStudentsByFilter($filter);

        $resource = $this->generator->fromArray(['students' => $studentList]);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
