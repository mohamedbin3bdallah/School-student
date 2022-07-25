<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Main\StudentMainController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Events\ReorderStudentsEmail;
use DB;
use Auth;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
		$return = new StudentMainController();
		$resources = $return->index();
		
        return view('students')->with(['resources'=>$resources]);
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
		$return = new StudentMainController();
		$resources = $return->create();
		
		return view('students.create')->with(['schools'=>$resources]);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $return = new StudentMainController();
		$check_unique = $return->store($request);
		
		switch ($check_unique) {
			case 0:
				return back()->withInput()->withErrors(['The name must be unique']);
				break;
			case 1:
				$return->model_store($request);
				return redirect()->route('students');
				break;
			case 2:
				return redirect()->route('students');
				break;
			default:
				return redirect()->route('students');
		}
    }
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
		$return = new StudentMainController();
		$resources = $return->edit($id);
		
		if($resources) return view('students.edit')->with(['resource'=>$resources['resource'], 'schools'=>$resources['schools']]);
		else return redirect()->route('students');
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
        $return = new StudentMainController();
		$check_unique = $return->update($request, $id);
			
		switch ($check_unique) {
			case 0:
				return back()->withInput()->withErrors(['The name must be unique']);
				break;
			case 1:
				$return->model_update($request, $id);
				return redirect()->route('students');
				break;
			case 2:
				return redirect()->route('students');
				break;
			case 3:
				return back()->withInput()->withErrors(['The student not exist']);
				break;
			default:
				return redirect()->route('students');
		}
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $return = new StudentMainController();
		$check_deleted = $return->destroy($id);
		
		switch ($check_deleted) {
			case 0:
				return back()->withErrors(['The student not deleted']);
				break;
			case 1:
				return redirect()->route('students');
				break;
			case 2:
				return back()->withErrors(['The student not exist']);
				break;
			default:
				return redirect()->route('students');
		}
    }
}
