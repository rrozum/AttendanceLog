<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class StudentLinkProgram extends Model
{
    protected $table = 'student_link_program';

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'program_id' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'student_id',
        'program_id',
    ];
}
