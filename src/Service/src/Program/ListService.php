<?php
declare(strict_types=1);

namespace Service\Program;


use App\Collection\ProgramCollection;

class ListService
{
    public function getAllProgram(): array
    {
        $resultPrograms = [];
        $offset = 0;

        while (true) {
            $programsFromDb = ProgramCollection::getProgramList($offset)->toArray();

            if(empty($programsFromDb)) {
                break;
            }

            $resultPrograms[] = $programsFromDb;

            if (count($programsFromDb) < ProgramCollection::MAX_LIMIT) {
                break;
            }

            $offset += ProgramCollection::MAX_LIMIT;
        }

        return $resultPrograms;
    }
}
