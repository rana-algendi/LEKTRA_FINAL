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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
           // $table->integer('image_id')->nullable();
            $table->integer('child_parent_id');
            $table->integer('doctor_id');
            $table->string('child_name')->nullable();
            $table->string('child_image')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('age')->nullable();
            $table->string('birthday')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('started_in')->nullable();
            $table->string('last_time')->nullable();
            $table->string('diagnosis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
