<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'hours',
        'location',
    ];

    protected $casts = [
        'hours' => 'integer',
        'id' => 'integer',
    ];
}
