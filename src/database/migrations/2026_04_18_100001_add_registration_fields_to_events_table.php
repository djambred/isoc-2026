<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('max_participants')->nullable()->after('capacity_info');
            $table->boolean('registration_open')->default(false)->after('max_participants');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['max_participants', 'registration_open']);
        });
    }
};
