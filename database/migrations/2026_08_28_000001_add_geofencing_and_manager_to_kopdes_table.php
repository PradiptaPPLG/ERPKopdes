<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kopdes', function (Blueprint $table) {
            $table->integer('radius_meter')->default(50)->after('longitude');
            $table->foreignId('manager_id')->nullable()->after('radius_meter')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kopdes', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['radius_meter', 'manager_id']);
        });
    }
};
