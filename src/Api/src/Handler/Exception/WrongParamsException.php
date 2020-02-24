<?php
declare(strict_types=1);

namespace Api\Handler\Exception;


use App\Error\AbstractProblemJsonError;

class WrongParamsException extends AbstractProblemJsonError
{
    protected $status = self::STATUS_BAD_REQUEST;
    protected $title = 'Wrong params';

    /**
     * @param string $paramName
     * @return AbstractProblemJsonError
     */
    public static function create(string $paramName = ''): AbstractProblemJsonError
    {
        $detail = "Param '{$paramName}' is wrong";
        return parent::create($detail);
    }
}
