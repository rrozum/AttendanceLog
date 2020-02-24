<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'school';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $fillable = [
        'name',
    ];
}
