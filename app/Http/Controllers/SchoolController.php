<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Main\SchoolMainController;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use DB;

class SchoolController extends Controller
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
		$return = new SchoolMainController();
		$resources = $return->index();
		
        return view('schools')->with(['resources'=>$resources]);
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
		$return = new SchoolMainController();
		$resources = $return->create();
		
        return view('schools.create')->with(['resources'=>$resources]);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
		$return = new SchoolMainController();
		$check_unique = $return->store($request);
		
		switch ($check_unique) {
			case 0:
				return back()->withInput()->withErrors(['The name must be unique']);
				break;
			case 1:
				$return->model_store($request);
				return redirect()->route('schools');
				break;
			case 2:
				return redirect()->route('schools');
				break;
			default:
				return redirect()->route('schools');
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
		$return = new SchoolMainController();
		$resources = $return->edit($id);
		
		if($resources) return view('schools.edit')->with(['resource'=>$resources['resource']]);
		else return redirect()->route('schools');
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
		$return = new SchoolMainController();
		$check_unique = $return->update($request, $id);
			
		switch ($check_unique) {
			case 0:
				return back()->withInput()->withErrors(['The name must be unique']);
				break;
			case 1:
				$return->model_update($request, $id);
				return redirect()->route('schools');
				break;
			case 2:
				return redirect()->route('schools');
				break;
			case 3:
				return back()->withInput()->withErrors(['The school not exist']);
				break;
			default:
				return redirect()->route('schools');
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
		$return = new SchoolMainController();
		$check_deleted = $return->destroy($id);
		
		switch ($check_deleted) {
			case 0:
				return back()->withErrors(['The school not deleted']);
				break;
			case 1:
				return redirect()->route('schools');
				break;
			case 2:
				return back()->withErrors(['The school not exist']);
				break;
			default:
				return redirect()->route('schools');
		}
    }
}
