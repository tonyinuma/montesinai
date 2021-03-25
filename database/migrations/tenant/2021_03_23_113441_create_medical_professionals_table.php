<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_professionals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('medical_department_id')->nullable();
            $table->integer('medical_speciality_id')->nullable();
            $table->integer('blood_type_id')->nullable();
            $table->integer('gender_id')->nullable();
            $table->integer('sex')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('identity_document_type_id');
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('document_number')->nullable();
            $table->date('birthday')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->integer('phone')->nullable();
            $table->integer('availability')->default(1)->nullable();
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
        Schema::dropIfExists('medical_professionals');
    }
}
