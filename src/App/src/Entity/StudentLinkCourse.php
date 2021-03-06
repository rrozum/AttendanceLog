<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class StudentLinkCourse extends Model
{
    protected $table = 'student_link_course';

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'course_id' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'student_id',
        'course_id',
    ];
}
