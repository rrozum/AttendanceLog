<?php
declare(strict_types=1);

namespace App\Collection;

use App\Entity\Course;
use Illuminate\Database\Eloquent\Collection;

class CourseCollection extends Collection
{
    const MAX_LIMIT = 500;

    /**
     * @param int $offset
     * @param int $limit
     * @return Course[]|Collection
     */
    public static function getCourseList(int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return Course::query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
