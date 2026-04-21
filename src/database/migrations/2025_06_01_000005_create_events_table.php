<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('category')->nullable(); // Webinar, Workshop, Panel Diskusi
            $table->string('image')->nullable();
            $table->date('date')->nullable();
            $table->json('time_info')->nullable(); // "14:00 - 16:00 WIB"
            $table->json('location')->nullable();
            $table->string('location_type')->default('offline'); // online, offline, hybrid
            $table->string('registration_url')->nullable();
            $table->json('capacity_info')->nullable(); // "250+ Peserta"
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
