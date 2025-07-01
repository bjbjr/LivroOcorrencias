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
        Schema::table('service_points', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('address'); // 10 total digits, 7 after decimal point
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude'); // 10 total digits, 7 after decimal point
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_points', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
