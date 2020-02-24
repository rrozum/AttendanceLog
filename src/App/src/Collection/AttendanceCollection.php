<?php
declare(strict_types=1);

namespace App\Collection;

use App\Entity\Attendance;
use Illuminate\Database\Eloquent\Collection;

class AttendanceCollection extends Collection
{
    const MAX_LIMIT = 500;

    /**
     * @param int $offset
     * @param int $limit
     * @return Attendance[]|Collection
     */
    public static function getAttendanceList(int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return Attendance::query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
