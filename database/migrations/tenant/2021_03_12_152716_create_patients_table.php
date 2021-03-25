<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('identity_document_type_id');
            $table->string('document_number')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable()->unique();
            $table->string('email')->nullable();
            $table->date('birthday')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('occupation')->nullable();
            $table->integer('age')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('phone')->nullable();
            $table->integer('gender_id')->nullable();
            $table->string('sex')->nullable();
            $table->integer('blood_type_id')->nullable();
            $table->integer('status_id')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
