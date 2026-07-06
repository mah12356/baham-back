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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('host_id');
            $table->string('game',300);
            $table->integer('players');
            $table->date('date');
            $table->time('time');
            $table->string('reward',300)->nullable();
            $table->string('city',300);
            $table->string('state',300);
            $table->string('area',300);
            $table->decimal('cost',23)->default(0);
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
