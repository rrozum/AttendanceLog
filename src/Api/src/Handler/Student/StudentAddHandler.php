<?php
declare(strict_types=1);

namespace Api\Handler\Student;


use Api\Handler\Exception\WrongParamsException;
use App\Entity\Course;
use App\Entity\Program;
use App\Entity\Student;
use App\Entity\StudentLinkCourse;
use App\Entity\StudentLinkProgram;
use Carbon\Carbon;
use Fig\Http\Message\StatusCodeInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class StudentAddHandler implements RequestHandlerInterface
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

        $name = $parsedBody['name'] ?? null;
        $birth = isset($parsedBody['birth']) ? (int)$parsedBody['birth'] : null;

        if (!empty($birth)) {
            $birth = Carbon::createFromTimestampUTC($birth)
                ->format('Y-m-d H:i:s');
        }

        $phone = isset($parsedBody['phone']) ? (string)$parsedBody['phone'] : null;

        $email = $parsedBody['email'] ?? null;
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        $schoolId = isset($parsedBody['school_id']) ? (int)$parsedBody['school_id'] : null;

        if (empty($name)) {
            throw WrongParamsException::create('name');
        }

        $student = Student::query()
            ->where('name', '=', $name)
            ->where('deleted', '=', true)
            ->first();

        if (!empty($student)) {
            $student->setAttribute('deleted', false);
        } else {
            $student = new Student();
        }

        $student->setAttribute('name', $name);
        $student->setAttribute('birth', $birth);
        $student->setAttribute('phone', $phone);
        $student->setAttribute('email', $email);
        $student->setAttribute('school_id', $schoolId);
        $student->save();

        $courseResource = $this->generator->fromArray(['id' => $student->getAttribute('id')]);

        if (!empty($parsedBody['program_id'])) {
            $programId = (int)$parsedBody['program_id'];

            $program = Program::query()->where('id', '=', $programId)->first();

            if (!empty($program)) {
                $studentLinkProgram = new StudentLinkProgram();
                $studentLinkProgram->setAttribute('student_id', $student->getAttribute('id'));
                $studentLinkProgram->setAttribute('program_id', $program->getAttribute('id'));
            } else {
                $student->delete();
                throw WrongParamsException::create('program_id');
            }
        }

        if (!empty($parsedBody['course_id'])) {
            $courseId = (int)$parsedBody['course_id'];

            $course = Course::query()->where('id', '=', $courseId)->first();

            if (!empty($course)) {
                $studentLinkCourse = new StudentLinkCourse();
                $studentLinkCourse->setAttribute('student_id', $student->getAttribute('id'));
                $studentLinkCourse->setAttribute('course_id', $course->getAttribute('id'));
            } else {
                $student->delete();
                throw WrongParamsException::create('course_id');
            }
        }

        // Если выше все отработало - сохраняем модели в бд
        if (!empty($studentLinkProgram)) {
            $studentLinkProgram->save();
        }

        if (!empty($studentLinkCourse)) {
            $studentLinkCourse->save();
        }

        $resource = $this->generator
            ->fromArray(['status' => 'success'])
            ->embed('student', $courseResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
