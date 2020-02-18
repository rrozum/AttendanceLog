<?php
declare(strict_types=1);

namespace Service\Student;

use App\Entity\Student;

/**
 * Этот сервис имеет методы для вывода списка студентов
 *
 * Class ListService
 * @package Service\Student
 */
class ListService
{
    /**
     * @return array
     */
    public function getAllStudentsAsArray(): array
    {
        return Student::query()->get()->toArray();
    }
}
