<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use App\Models\Student;

class StudentApiTest extends TestCase
{
    /**
     * A test of get all students.
     *
     * @return void
    */
    public function test_get_all_students()
    {
		$user = User::first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->getJson('/api/students')
						 ->assertStatus(201)
						 ->assertJson(['status'=>true])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'data'])
						 );
    }
	
	/**
     * A test of storing a student without input.
     *
     * @return void
    */
    public function test_storing_a_student_without_input()
    {
		$user = User::first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->postJson('/api/student/store')
						 ->assertStatus(422)
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['message', 'errors'])
						 );
    }
	
	/**
     * A test of storing a not existing or restoring student.
     *
     * @return void
    */
    public function test_storing_a_not_existing_or_restoring_student()
    {
		$user = User::first();
		$school = School::first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->postJson('/api/student/store', ['name'=>'Store Student Test', 'school_id'=>$school->id])
						 ->assertStatus(201)
						 ->assertJson(['status'=>true])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'message'])
						 );
    }
	
	/**
     * A test of storing an existing student.
     *
     * @return void
    */
    public function test_storing_an_existing_student()
    {
		$user = User::first();
		$school = School::first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->postJson('/api/student/store', ['name'=>'Store Student Test', 'school_id'=>$school->id])
						 ->assertStatus(401)
						 ->assertJson(['status'=>false])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'message'])
						 );
    }
	
	/**
     * A test of updating a student without input.
     *
     * @return void
    */
    public function test_updating_a_student_without_input()
    {
		$user = User::first();
		$student = Student::where(['name'=>'Store Student Test'])->first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->postJson('/api/student/update/'.$student->id)
						 ->assertStatus(422)
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['message', 'errors'])
						 );
    }
	
	/**
     * A test of updating an existing or restoring student.
     *
     * @return void
    */
    public function test_updating_an_existing_or_restoring_student()
    {
		$user = User::first();
		$student = Student::where(['name'=>'Store Student Test'])->first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->postJson('/api/student/update/'.$student->id, ['name'=>'Update Student Test', 'school_id'=>$student->school_id])
						 ->assertStatus(201)
						 ->assertJson(['status'=>true])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'message'])
						 );
    }
	
	/**
     * A test of updating a not existing student.
     *
     * @return void
    */
    public function test_updating_a_not_existing_student()
    {
		$user = User::first();
		$school = School::first();
		$max_student_id = Student::max('id');
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->postJson('/api/student/update/'.$max_student_id+1, ['name'=>'Update Student Test', 'school_id'=>$school->id])
						 ->assertStatus(401)
						 ->assertJson(['status'=>false])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'message'])
						 );
    }
	
	/**
     * A test of deleteing an existing student.
     *
     * @return void
    */
    public function test_deleteing_an_existing_student()
    {
		$user = User::first();
		$student = Student::where(['name'=>'Update Student Test'])->first();
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->deleteJson('/api/student/delete/'.$student->id)
						 ->assertStatus(201)
						 ->assertJson(['status'=>true])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'message'])
						 );
    }
	
	/**
     * A test of deleteing a not existing student.
     *
     * @return void
    */
    public function test_deleteing_a_not_existing_student()
    {
		$user = User::first();
		$max_student_id = Student::max('id');
		
        $response = $this->actingAs($user, 'api')
						 ->withHeaders([
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Accept' => 'application/json',
							'Authorization' => 'Bearer '.$user->createToken('ApiToken')->plainTextToken,
						 ])
						 ->deleteJson('/api/student/delete/'.$max_student_id+1)
						 ->assertStatus(401)
						 ->assertJson(['status'=>false])
						 ->assertJson(fn (AssertableJson $json) =>
							$json->hasAll(['status', 'message'])
						 );
    }
}
