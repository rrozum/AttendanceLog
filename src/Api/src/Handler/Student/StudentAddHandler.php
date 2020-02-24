<?php
declare(strict_types=1);

namespace Api\Handler\Student;


use Api\Handler\Exception\WrongParamsException;
use App\Entity\Student;
use Carbon\Carbon;
use Fig\Http\Message\StatusCodeInterface;
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

        $resource = $this->generator
            ->fromArray(['status' => 'success'])
            ->embed('student', $courseResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
