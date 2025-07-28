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
        Schema::create('beta_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('email', 100);
            $table->string('name');
            $table->string('phone', 20);
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beta_appointments');
    }
};
