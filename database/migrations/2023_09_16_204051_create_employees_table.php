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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique();
            $table->string('name_prefix')->nullable();
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('last_name');
            $table->string('gender');
            $table->string('email');
            $table->date('date_of_birth');
            $table->time('time_of_birth');
            $table->decimal('age_in_yrs', 8, 2);
            $table->date('date_of_joining');
            $table->decimal('age_in_company_years', 8, 2);
            $table->string('phone_no');
            $table->string('place_name');
            $table->string('county');
            $table->string('city');
            $table->string('zip');
            $table->string('region');
            $table->string('user_name');
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
        Schema::dropIfExists('employees');
    }
};
