<?php
declare(strict_types=1);

namespace Service\Student;

use App\Collection\StudentsCollection;
use App\Entity\Attendance;
use App\Entity\Student;
use App\Entity\StudentLinkCourse;
use App\Entity\StudentLinkProgram;
use App\Model\FilterModel;
use Carbon\Carbon;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * Этот сервис имеет методы для вывода списка студентов
 *
 * Class ListService
 * @package Service\Student
 */
class ListService
{
    public function getStudentsByFilter(FilterModel $filterModel): array
    {
        $studentIds = [];

        // Тригер показывает была ли поптыка применить фильтр
        // если фильтр не был применен, то показываем всех студентов
        // если была попытка применить фильтр, но ничего не нашли, то выводим пустоту
        // тригер переключаем только если ничего не нашли при попытке применить фильтр
        $filterCheck = false;

        $programIds = $filterModel->getProgramId();
        $courseIds = $filterModel->getCourseId();
        $schoolIds = $filterModel->getSchoolId();
        $dateFrom = $filterModel->getDateFrom();
        $dateTo = $filterModel->getDateTo();

        if (!empty($programIds)) {
            $studentIdsByProgram = StudentLinkProgram::query()
                ->whereIn('program_id', $programIds)
                ->get(['student_id']);

            if (!empty($studentIdsByProgram->toArray())) {
                $studentIdsByProgram = $studentIdsByProgram->implode('student_id', ',');
                $studentIdsByProgram = explode(',', $studentIdsByProgram);
                $studentIds = $studentIdsByProgram;
            } else {
                $filterCheck = true;
            }
        }

        if (!empty($schoolIds)) {
            $studentIdsBySchool = Student::query()->whereIn('school_id', $schoolIds);

            if (!empty($studentIds)) {
                $studentIdsBySchool = $studentIdsBySchool->whereIn('id', $studentIds);
            }
                $studentIdsBySchool = $studentIdsBySchool->get('id');

            if (!empty($studentIdsBySchool->toArray())) {
                $studentIdsBySchool = $studentIdsBySchool->implode('id', ',');
                $studentIdsBySchool = explode(',', $studentIdsBySchool);
                $studentIds = $studentIdsBySchool;
            } else {
                $filterCheck = true;
            }
        }

        if (!empty($courseIds)) {
            $studentIdsByCourse = StudentLinkCourse::query()
                ->whereIn('course_id', $courseIds);

            if (!empty($studentIds)) {
                $studentIdsByCourse = $studentIdsByCourse->whereIn('student_id', $studentIds);
            }

            $studentIdsByCourse = $studentIdsByCourse->get(['student_id']);

            if (!empty($studentIdsByCourse->toArray())) {
                $studentIdsByCourse = $studentIdsByCourse->implode('student_id', ',');
                $studentIdsByCourse = explode(',', $studentIdsByCourse);
                $studentIds = $studentIdsByCourse;
            } else {
                $filterCheck = true;
            }
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $dateFrom = Carbon::createFromTimestampUTC($dateFrom)
                ->format('Y-m-d H:i:s');
            $dateTo = Carbon::createFromTimestampUTC($dateTo)
                ->format('Y-m-d H:i:s');

            $attendance = Attendance::query();
            if (!empty($studentIds)) {
                $attendance = $attendance->whereIn('student_id', $studentIds);
            }

            $attendance = $attendance->whereBetween('date', [$dateFrom, $dateTo])->get();

            if (!empty($attendance->toArray())) {
                $studentIdsByAttendance = $attendance->implode('student_id', ',');
                $studentIdsByAttendance = explode(',', $studentIdsByAttendance);
                $studentIds = $studentIdsByAttendance;
            } else {
                $filterCheck = true;
            }
        }

        if (empty($studentIds) && $filterCheck === false) {
            $students = Student::all()->toArray();
        } else {
            $students = Student::query()
                ->whereIn('id', $studentIds)
                ->get()
                ->toArray();
        }

        return $students;
    }
}
