<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
	use SoftDeletes;
	
	/**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'students';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'name',
		'school_id',
		'order',
    ];
	
	/**
	 * Get the school that owns the student.
	*/
	public function school()
	{
		return $this->belongsTo(School::class, 'school_id');
	}
}
