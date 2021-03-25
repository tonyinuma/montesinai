<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('allocator_user_id');
            $table->integer('quotation_id')->nullable();
            $table->integer('patient_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->integer('medical_department_id')->nullable();
            $table->integer('room_type')->nullable();
            $table->string('room_number')->nullable();
            $table->string('origin')->nullable();
            $table->date('admission_date')->nullable();
            $table->time('admission_time')->nullable();
            $table->date('departure_date')->nullable();
            $table->time('departure_time')->nullable();
            $table->string('observation')->nullable();
            $table->float('price', 8, 2)->nullable();
            $table->integer('status')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('appointments');
    }
}
