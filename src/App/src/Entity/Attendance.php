<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'attendance' => 'bool',
    ];

    protected $fillable = [
        'student_id',
        'date',
        'attendance',
    ];
}
