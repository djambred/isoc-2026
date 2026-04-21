<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('page'); // home, about, programs, events, ourpartner
            $table->string('key'); // hero, mission, impact_stats, etc.
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            $table->string('image')->nullable();
            $table->json('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->json('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['page', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
