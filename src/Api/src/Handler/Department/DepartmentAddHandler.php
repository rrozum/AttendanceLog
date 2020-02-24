<?php
declare(strict_types=1);

namespace Api\Handler\Department;


use Api\Handler\Exception\RecordExistsException;
use Api\Handler\Exception\WrongParamsException;
use App\Entity\Department;
use App\Entity\Program;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class DepartmentAddHandler implements RequestHandlerInterface
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

        $department = Department::query()
            ->where('name', '=', $name)
            ->first();

        if (!empty($department)) {
            throw RecordExistsException::create($department->getAttribute('name'));
        }

        $department = new Department();
        $department->setAttribute('name', $name);
        $department->save();

        $departmentResource = $this->generator->fromArray(['id' => $department->getAttribute('id')]);

        $resource = $this->generator
            ->fromArray(['status' => 'success'])
            ->embed('department', $departmentResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
