<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name', 191);
			$table->unsignedBigInteger('school_id');
			$table->foreign('school_id')->references('id')->on('schools');
			//$table->foreignId('school_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
			$table->unsignedBigInteger('order')->default(0);
            $table->timestamps();
			$table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
