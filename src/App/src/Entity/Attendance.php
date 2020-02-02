<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'date',
        'attend'
    ];

    protected $dates = [
        'date',
    ];

    protected $casts = [
        'attend' => 'boolean',
    ];
}
