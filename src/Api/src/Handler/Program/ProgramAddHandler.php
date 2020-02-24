<?php
declare(strict_types=1);

namespace Api\Handler\Program;


use Api\Handler\Exception\RecordExistsException;
use Api\Handler\Exception\WrongParamsException;
use App\Collection\DepartmentCollection;
use App\Entity\Program;
use App\Entity\ProgramLinkDepartment;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

class ProgramAddHandler implements RequestHandlerInterface
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
        $departmentIds = $parsedBody['department_ids'] ?? null;

        if (empty($name)) {
            throw WrongParamsException::create('name');
        }

        if (empty($departmentIds)) {
            throw WrongParamsException::create('department_ids');
        }

        $departments = DepartmentCollection::getDepartmentsByIds($departmentIds)
            ->toArray();

        if (count($departmentIds) > count($departments)) {
            throw WrongParamsException::create('department_ids');
        }

        $program = Program::query()
            ->where('name', '=', $name)
            ->first();

        if (!empty($program)) {
            throw RecordExistsException::create($program->getAttribute('name'));
        }

        $program = new Program();
        $program->setAttribute('name', $name);
        $program->save();

        foreach ($departmentIds as $departmentId) {
            $programLinkDepartment = new ProgramLinkDepartment();
            $programLinkDepartment->setAttribute('program_id', $program->getAttribute('id'));
            $programLinkDepartment->setAttribute('department_id', (int)$departmentId);
            $programLinkDepartment->save();
        }

        $departmentElements = [];
        foreach ($departments as $department) {
            $departmentId = $department['id'];
            $departmentName = $department['name'];

            $departmentElements[$departmentId] = $departmentName;
        }
        $programResource = $this->generator->fromArray([
            'id' => $program->getAttribute('id'),
            'name' => $program->getAttribute('name'),
            'department_resource' => $departmentElements
        ]);

        $resource = $this->generator->fromArray(['status' => 'success'])->embed('program_resource', $programResource);

        return $this->halResponseFactory
            ->createResponse($request, $resource)
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
