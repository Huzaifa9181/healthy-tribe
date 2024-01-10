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
        Schema::create('motivations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addiction_id')->nullable(); // ID of the group leader
            $table->string('image')->nullable(); // ID of the group leader
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivations');
    }
};
