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

class StudentAdd implements RequestHandlerInterface
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
        $requestBody = $request->getParsedBody();

        $name = $requestBody['name'] ?? null;
        $birth = $requestBody['birth'] ?? null;
        $phone = $requestBody['phone'] ?? null;
        $email = $requestBody['email'] ?? null;
        $type = $requestBody['type'] ?? null;
        $courseId = $requestBody['course_id'] ?? null;
        $schoolId = $requestBody['school_id'] ?? null;

        if (empty($name) || empty($birth) || empty($courseId) || empty($schoolId)) {
            throw new \InvalidArgumentException();
        }

        $studentArray = [
            'name' => $name,
            'birth' => (int)$birth,
            'phone' => (string)$phone,
            'email' => $email,
            'type' => $type,
            'course_id' => $courseId,
            'school_id' => $schoolId,
        ];

        $studentModel = new Student($studentArray);

        $studentModel->save();

        $studentResource = $this->generator->fromArray($studentArray);
        $resource = $this->generator->fromArray(['status' => 'ok'])
            ->embed('student', $studentResource);

        return $this->halResponseFactory->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
