<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'map_lat')) {
                $table->string('map_lat', 30)->nullable()->after('zip_code');
            }
            if (!Schema::hasColumn('users', 'map_lng')) {
                $table->string('map_lng', 30)->nullable()->after('map_lat');
            }
            if (!Schema::hasColumn('users', 'map_zoom')) {
                $table->string('map_zoom', 10)->nullable()->after('map_lng');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'map_zoom')) {
                $table->dropColumn('map_zoom');
            }
            if (Schema::hasColumn('users', 'map_lng')) {
                $table->dropColumn('map_lng');
            }
            if (Schema::hasColumn('users', 'map_lat')) {
                $table->dropColumn('map_lat');
            }
        });
    }
};
