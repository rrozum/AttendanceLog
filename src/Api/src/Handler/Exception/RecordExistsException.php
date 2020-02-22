<?php
declare(strict_types=1);

namespace Api\Handler\Exception;


use App\Error\AbstractProblemJsonError;

class RecordExistsException extends AbstractProblemJsonError
{
    protected $status = self::STATUS_CONFLICT;
    protected $title = 'Record already exist';

    /**
     * @param string $paramName
     * @return AbstractProblemJsonError
     */
    public static function create(string $paramName = ''): AbstractProblemJsonError
    {
        $detail = "Record '{$paramName}' exists";
        return parent::create($detail);
    }
}
