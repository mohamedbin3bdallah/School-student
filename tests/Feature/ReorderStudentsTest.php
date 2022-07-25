<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\School;

class ReorderStudentsTest extends TestCase
{
    /**
     * A test of reordring students of given school
     *
     * @return void
     */
    public function test_reordering_students_of_given_school()
    {
		$school = School::first();
		$this->artisan('students:reorder', ['school' => $school->id])->assertExitCode(1);
    }
	
	/**
     * A test of reordring students of not given school
     *
     * @return void
     */
    public function test_reordering_students_of_not_given_school()
    {
		$this->artisan('students:reorder')->assertExitCode(0);
    }
}
