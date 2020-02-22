<?php
declare(strict_types=1);

namespace App\Collection;


use App\Entity\Student;
use Illuminate\Database\Eloquent\Collection;

class StudentsCollection extends Collection
{
    const MAX_LIMIT = 500;

    /**
     * @param int $offset
     * @param int $limit
     * @return Student[]|Collection
     */
    public static function getStudentList(int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return Student::query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
