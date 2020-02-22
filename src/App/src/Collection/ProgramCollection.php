<?php
declare(strict_types=1);

namespace App\Collection;

use App\Entity\Program;
use Illuminate\Database\Eloquent\Collection;

class ProgramCollection extends Collection
{
    const MAX_LIMIT = 500;

    /**
     * @param int $offset
     * @param int $limit
     * @return Program[]|Collection
     */
    public static function getProgramList(int $offset = 0, int $limit = self::MAX_LIMIT) {
        $limit = min($limit, self::MAX_LIMIT);

        return Program::query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
