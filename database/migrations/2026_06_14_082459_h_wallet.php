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
        Schema::create('h_wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('host_id');
            $table->decimal('irt',23)->default(0);
            $table->decimal('Birt',23)->default(0);
        });
    }

    public function down(): void{
        //
    }
};
