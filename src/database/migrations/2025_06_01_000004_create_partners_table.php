<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->json('description')->nullable();
            $table->json('subtitle')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_url')->nullable(); // external logo URL
            $table->string('url')->nullable();
            $table->string('type')->default('international'); // international, national
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
