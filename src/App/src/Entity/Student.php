<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';

    protected $dates = [
        'birth',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'deleted' => 'bool',
    ];

    protected $fillable = [
        'name',
        'birth',
        'phone',
        'email',
        'school_id',
        'course_id',
        'program_id',
        'deleted'
    ];
}
