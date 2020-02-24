<?php
declare(strict_types=1);

namespace Api\Handler\School;


use Api\Handler\Exception\RecordExistsException;
use Api\Handler\Exception\WrongParamsException;
use App\Entity\School;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class SchoolAddHandler implements RequestHandlerInterface
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

        $school = School::query()
            ->where('name', '=', $name)
            ->first();

        if (!empty($school)) {
            throw RecordExistsException::create($school->getAttribute('name'));
        }

        $school = new School();
        $school->setAttribute('name', $name);
        $school->save();

        $courseResource = $this->generator->fromArray(['id' => $school->getAttribute('id')]);

        $resource = $this->generator
            ->fromArray(['status' => 'success'])
            ->embed('school', $courseResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
