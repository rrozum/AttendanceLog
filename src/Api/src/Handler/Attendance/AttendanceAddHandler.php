<?php
declare(strict_types=1);

namespace Api\Handler\Attendance;


use Api\Handler\Exception\RecordExistsException;
use Api\Handler\Exception\WrongParamsException;
use App\Entity\Attendance;
use App\Entity\Course;
use Carbon\Carbon;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class AttendanceAddHandler implements RequestHandlerInterface
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
        $contents = $request->getBody()->getContents();
        $parsedBody = json_decode($contents, true);

        $studentId = $parsedBody['student_id'] ?? null;
        $date = isset($parsedBody['date']) ? (int)$parsedBody['date'] : null;
        $attendance = $parsedBody['attendance'] ?? null;
        $attendance = filter_var($attendance, FILTER_VALIDATE_BOOLEAN);

        if (empty($studentId)) {
            throw WrongParamsException::create('student_id');
        }
        if (empty($date)) {
            throw WrongParamsException::create('date');
        }

        $date = Carbon::createFromTimestampUTC($date)
            ->format('Y-m-d H:i:s');

        $attendanceRow = Attendance::query()
            ->where('student_id', '=', $studentId)
            ->where('date', '=', $date)
            ->first();

        if (empty($attendanceRow)) {
            $attendanceRow = new Attendance();
        }

        $attendanceRow->setAttribute('student_id', $studentId);
        $attendanceRow->setAttribute('date', $date);
        $attendanceRow->setAttribute('attendance', $attendance);
        $attendanceRow->save();

        $attendanceResource = $this->generator->fromArray(['id' => $attendanceRow->getAttribute('id')]);

        $resource = $this->generator
            ->fromArray(['status' => 'success'])
            ->embed('attendance', $attendanceResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
