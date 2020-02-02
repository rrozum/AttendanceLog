<?php
declare(strict_types=1);

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'direction',
        'location',
    ];
}
