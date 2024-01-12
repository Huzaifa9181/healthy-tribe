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
        Schema::create('addiction_managements', function (Blueprint $table) {
            $table->id();
            $table->string("option");
            $table->unsignedBigInteger("option_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("addiction_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addiction_managements');
    }
};
