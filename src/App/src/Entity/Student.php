<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'id';

    protected $dates = [
        'birth',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $fillable = [
        'name',
        'birth',
        'phone',
        'email',
        'type',
        'course_id',
        'school_id',
    ];
}
