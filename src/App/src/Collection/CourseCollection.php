<?php
declare(strict_types=1);

namespace App\Collection;

use App\Entity\Course;
use App\Entity\Department;
use Illuminate\Database\Eloquent\Collection;

class CourseCollection extends Collection
{
    const MAX_LIMIT = 500;

    /**
     * @param int $offset
     * @param int $limit
     * @return Department[]|Collection
     */
    public static function getCourseList(int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return Course::query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public static function getCourseByIds(array $ids, int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return Course::query()
            ->whereIn('id', $ids, 'or')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
