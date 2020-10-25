<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'status',
        'description'
    ];

    public $timestamps = false;

    public function rewards()
    {
        return $this->hasMany(Reward::class, 'projectId', 'id');
    }
}
