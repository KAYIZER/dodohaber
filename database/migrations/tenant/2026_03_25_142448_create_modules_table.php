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
        Schema::create('modules', function (Blueprint $create) {
            $create->id();
            $create->string('name'); // e.g., 'pharmacy'
            $create->string('label')->nullable(); // e.g., 'Nöbetçi Eczane'
            $create->boolean('is_active')->default(false);
            $create->json('settings')->nullable(); // JSON data for specific module settings
            $create->string('slug')->nullable(); // Custom URL slug for the module
            $create->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
