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
        Schema::create('hosts', function (Blueprint $table) {
            $table->id();
            $table->string('username',300);
            $table->string('national_code',300);
            $table->string('phone',300);
            $table->string('password',300);
            $table->string('address',300);
            $table->string('city',300);
            $table->string('state',300);
            $table->string('area',300);
            $table->string('photo',300);
            $table->string('shaba',300)->nullable();
            $table->decimal('likes',23)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
