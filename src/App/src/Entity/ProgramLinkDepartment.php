<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class ProgramLinkDepartment extends Model
{
    protected $table = 'program_link_department';

    protected $casts = [
        'id' => 'integer',
        'program_id' => 'integer',
        'department_id' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'program_id',
        'department_id',
    ];
}
