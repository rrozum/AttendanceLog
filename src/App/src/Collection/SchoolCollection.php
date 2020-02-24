<?php
declare(strict_types=1);

namespace App\Collection;

use App\Entity\School;
use Illuminate\Database\Eloquent\Collection;

class SchoolCollection extends Collection
{
    const MAX_LIMIT = 500;

    /**
     * @param int $offset
     * @param int $limit
     * @return School[]|Collection
     */
    public static function getSchoolList(int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return School::query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
