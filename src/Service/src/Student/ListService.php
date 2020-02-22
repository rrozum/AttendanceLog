<?php
declare(strict_types=1);

namespace Service\Student;

use App\Collection\StudentsCollection;

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
    public function getAllStudents(): array
    {
        $resultStudents = [];
        $offset = 0;

        while (true) {
            $studentsFromDb = StudentsCollection::getStudentList($offset)->toArray();

            if(empty($studentsFromDb)) {
                break;
            }

            $resultStudents[] = $studentsFromDb;

            if (count($studentsFromDb) < StudentsCollection::MAX_LIMIT) {
                break;
            }

            $offset += StudentsCollection::MAX_LIMIT;
        }

        return $resultStudents;
    }
}
