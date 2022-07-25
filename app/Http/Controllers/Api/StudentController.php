<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
		$return = new StudentMainController();
		$resources = $return->index();
		
		$response = ['status'=>true, 'data' => $resources];

        return response($response, 201);
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
		return response(['message'=>'Request not exist'], 404);
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
				return response(['status'=>false, 'message'=>'The name must be unique'], 401);
				break;
			case 1:
				$return->model_store($request);
				return response(['status'=>true, 'message'=>'The student saved successfully'], 201);
				break;
			case 2:
				return response(['status'=>true, 'message'=>'The student restored successfully'], 201);
				break;
			default:
				return response(['status'=>false, 'message'=>'Nothing'], 401);
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
		return response(['message'=>'Request not exist'], 404);
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
				return response(['status'=>false, 'message'=>'The name must be unique'], 401);
				break;
			case 1:
				$return->model_update($request, $id);
				return response(['status'=>true, 'message'=>'The student updated successfully'], 201);
				break;
			case 2:
				return response(['status'=>true, 'message'=>'The student restored successfully'], 201);
				break;
			case 3:
				return response(['status'=>false, 'message'=>'The student not exist'], 401);
				break;
			default:
				return response(['status'=>false, 'message'=>'Nothing'], 401);
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
				return response(['status'=>false, 'message'=>'The student not deleted'], 401);
				break;
			case 1:
				return response(['status'=>true, 'message'=>'The student deleted successfully'], 201);
				break;
			case 2:
				return response(['status'=>false, 'message'=>'The student not exist'], 401);
				break;
			default:
				return response(['status'=>false, 'message'=>'Nothing'], 401);
		}
    }
}
