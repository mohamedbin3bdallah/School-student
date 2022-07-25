<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Events\ReorderStudentsEmail;
use DB;
use Auth;

class StudentMainController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return resources
    */
    public function index()
    {
		$resources = Student::orderBy('school_id')->orderBy('order')->get();
		return $resources;
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
		$resources = School::pluck('name', 'id')->toArray();
		return $resources;
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
			//'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191|unique:students',
			'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191',
			'school_id' => 'required|integer|gt:0|exists:schools,id,deleted_at,NULL',
		]);
		
		$check_unique = $this->checkUniqueAndRestore($request->name, $request->school_id, 0);
		
		return $check_unique;
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function model_store(Request $request)
    {
        Student::create([
			'name' => $request->name,
			'school_id' => $request->school_id,
			'order' => Student::where(['school_id'=>$request->school_id])->max('order') + 1,
		]);
    }
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
		$resources['resource'] = Student::find($id);
		$resources['schools'] = School::pluck('name', 'id')->toArray();
		return $resources;
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $resource = Student::find($id);
		if($resource)
		{
			$validated = $request->validate([
				//'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191|unique:students,name,'.$id,
				'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191',
				'school_id' => 'required|integer|gt:0|exists:schools,id,deleted_at,NULL',
			]);
			
			$check_unique = $this->checkUniqueAndRestore($request->name, $request->school_id, $id);
		}
		else $check_unique = 3;
		
		return $check_unique;
    }
	
	/**
     * Update the specified resource in storage.
     *
	 * @param  $student_id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function model_update(Request $request, $student_id)
    {
		$resource = Student::find($student_id);
		$old_school_id = $resource->school_id;
		$resource->update([
			'name' => $request->name,
			'school_id' => $request->school_id,
		]);
		$this->commandReorder($request->school_id);
		$this->commandReorder($old_school_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $resource = Student::find($id);
		if($resource)
		{
			$check_delete = $resource->delete();
			if($check_delete) $this->commandReorder($resource->school_id);
		}
		else $check_delete = 2;
		return $check_delete;
    }
	
	/**
     * Check if student name repeated in the same school, restore and delete
     *
     * @param  string  $name
	 * @param  int  $school_id
     * @param  int  $student_id
     * @return 0,1,2
    */	
	public function checkUniqueAndRestore($name, $school_id, $student_id=0)
	{
		$query = DB::table('students')->where(['name'=>$name, 'school_id'=>$school_id]);
		if($student_id)
		{
			$query->where('id', '!=', $student_id);
			$student = Student::find($student_id);
		}
		$resource = $query->first();
		
		if($resource)
		{
			if($resource->deleted_at != NULL)
			{
				Student::withTrashed()->find($resource->id)->restore();
				$this->commandReorder($school_id);
				
				if(isset($student) and $student)
				{
					$student->delete();
					$this->commandReorder($student->school_id);
				}
				
				return 2;
			}
			else return 0;
		}
		else return 1;
	}
	
	/**
     * Command: Reorder students of given school
     *
	 * @param  int  $school_id
    */	
	public function commandReorder($school_id)
	{
		Artisan::call('students:reorder', [
			'school' => $school_id
		]);
		ReorderStudentsEmail::dispatch(User::find(Auth::user()->id));
	}
}
