<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class ReorderStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
    */
    protected $signature = 'students:reorder {school?}';

    /**
     * The console command description.
     *
     * @var string
    */
    protected $description = 'Reorder School Students';

    /**
     * Execute the console command.
     *
     * @return int
    */
    public function handle()
    {
        if($this->argument('school'))
		{
			$school = $this->argument('school');
			$students = Student::where(['school_id'=>$school])->get();
			foreach($students as $key => $student)
			{
				$student->update(['order'=>$key + 1]);
			}
			return 1;
		}
		else return 0;
    }
}
