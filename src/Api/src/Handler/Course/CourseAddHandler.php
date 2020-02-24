<?php
declare(strict_types=1);

namespace Api\Handler\Course;


use Api\Handler\Exception\RecordExistsException;
use Api\Handler\Exception\WrongParamsException;
use App\Entity\Course;
use App\Entity\Department;
use App\Entity\Program;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class CourseAddHandler implements RequestHandlerInterface
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

        if (empty($name)) {
            throw WrongParamsException::create('name');
        }

        $course = Course::query()
            ->where('name', '=', $name)
            ->first();

        if (!empty($course)) {
            throw RecordExistsException::create($course->getAttribute('name'));
        }

        $course = new Course();
        $course->setAttribute('name', $name);
        $course->save();

        $courseResource = $this->generator->fromArray(['id' => $course->getAttribute('id')]);

        $resource = $this->generator
            ->fromArray(['status' => 'success'])
            ->embed('course', $courseResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
