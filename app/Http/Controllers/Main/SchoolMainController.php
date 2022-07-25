<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Main\StudentMainController;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use DB;

class SchoolMainController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
		$resources = School::all();
        return $resources;
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $resources = [];
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
			//'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191|unique:schools',
	'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191',
		]);
		
		$check_unique = $this->checkUniqueAndRestore($request->name, 0);
		
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
        School::create([
			'name' => $request->name,
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
		$resources['resource'] = School::find($id);
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
        $resource = School::find($id);
		if($resource)
		{
			$validated = $request->validate([
				//'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191|unique:schools,name,'.$id,
				'name' => 'required|regex:/^([^0-9]*)$/|regex:/^([^!@#$%^&*()_+-=<>?":{}]*)$/|max:191',
			]);
			
			$check_unique = $this->checkUniqueAndRestore($request->name, $id);
		}
		else $check_unique = 3;
		
		return $check_unique;
    }
	
	/**
     * Update the specified resource in storage.
     *
	 * @param  $school_id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    */
    public function model_update(Request $request, $school_id)
    {
		$resource = School::find($school_id);
		$resource->update([
			'name' => $request->name,
		]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
		$resource = School::find($id);
		if($resource)
		{
			$check_delete = $resource->delete();
			if($check_delete) Student::where(['school_id'=>$id])->delete();
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
	public function checkUniqueAndRestore($name, $school_id=0)
	{
		$query = DB::table('schools')->where(['name'=>$name]);
		if($school_id)
		{
			$query->where('id', '!=', $school_id);
			$school = School::find($school_id);
		}
		$resource = $query->first();
		
		if($resource)
		{
			if($resource->deleted_at != NULL)
			{
				School::withTrashed()->find($resource->id)->restore();
				Student::withTrashed()->where(['school_id'=>$resource->id])->restore();
				
				$reorder_students = new StudentMainController();
				$reorder_students->commandReorder($resource->id);
				
				if(isset($school) and $school)
				{
					$school->delete();
					Student::where(['school_id'=>$school->id])->delete();
				}
				
				return 2;
			}
			else return 0;
		}
		else return 1;
	}
}
