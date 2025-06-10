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
        Schema::create('presidential_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->string('political_party')->nullable(); // null if independent
            $table->string('national_id')->unique();
            $table->string('region');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable(); // path to image
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presidential_candidates');
    }
};
