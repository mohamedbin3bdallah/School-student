<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
	use SoftDeletes;
	
	/**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'schools';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'name',
    ];
	
	/**
     * Get the students for the school.
    */
    public function student()
    {
        return $this->hasMany(Student::class, 'id', 'school_id');
    }
}
