<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->char('middle_initial', 1)->nullable();
            $table->enum('gender', ['F', 'M'])->nullable();
            $table->string('email')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->time('time_of_birth')->nullable();
            $table->double('age_in_years', 5, 2)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('place_name')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->double('age_in_company_in_years', 5, 2)->nullable();
            $table->unsignedBigInteger('prefix_id')->nullable();
            $table->foreign('prefix_id')->references('id')->on('prefixes')
                ->onDelete('set null');
            $table->unsignedBigInteger('zip_code_id')->nullable();
            $table->foreign('zip_code_id')->references('id')->on('zip_codes')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};