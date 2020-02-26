<?php
declare(strict_types=1);

namespace App\Model;


class FilterModel
{
    protected $fields = [
        'program_id' => null,
        'course_id' => null,
        'school_id' => null,
        'date' => [
            'from' => null,
            'to' => null
        ],
        'attendance' => null
    ];

    public function __construct(?array $programId, ?array $courseId, ?array $schoolId,?array $date, ?bool $attendance)
    {
        if (!empty($programId)) {
            $this->setProgramId($programId);
        }

        if (!empty($courseId)) {
            $this->setCourseId($courseId);
        }

        if (!empty($schoolId)) {
            $this->setSchoolId($schoolId);
        }

        if (!empty($date)) {
            $this->setDate($date);
        }

        if (!empty($attendance)) {
            $this->setAttendance($attendance);
        }

    }

    /**
     * @param int $programId
     */
    public function setProgramId(array $programId): void
    {
        $this->fields['program_id'] = $programId;
    }

    /**
     * @return int|null
     */
    public function getProgramId(): ?array
    {
        return $this->fields['program_id'];
    }

    public function setCourseId(array $courseId): void
    {
        $this->fields['course_id'] = $courseId;
    }

    public function getCourseId(): ?array
    {
        return $this->fields['course_id'];
    }

    public function setSchoolId(array $schoolId): void
    {
        $this->fields['school_id'] = $schoolId;
    }

    public function getSchoolId(): ?array
    {
        return $this->fields['school_id'];
    }

    /**
     * @param array $date
     */
    public function setDate(array $date): void
    {
        if (isset($date['from'])) {
            $this->fields['date']['from'] = (int)$date['from'];
        }
        if (isset($date['to'])) {
            $this->fields['date']['to'] = (int)$date['to'];
        }
    }

    /**
     * @return array
     */
    public function getDate(): array
    {
        return $this->fields['date'];
    }

    public function getDateFrom(): ?int
    {
        return $this->fields['date']['from'];
    }

    public function getDateTo(): ?int
    {
        return $this->fields['date']['to'];
    }

    public function setAttendance(bool $attendance): void
    {
        $this->fields['attendance'] = $attendance;
    }

    public function getAttendance(): bool
    {
        return $this->fields['attendance'];
    }
}
