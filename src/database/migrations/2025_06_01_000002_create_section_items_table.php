<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->string('icon')->nullable(); // material icon name
            $table->string('icon_color')->nullable(); // blue, teal, navy
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->json('extra_data')->nullable(); // flexible JSON for stat values, etc.
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_items');
    }
};
