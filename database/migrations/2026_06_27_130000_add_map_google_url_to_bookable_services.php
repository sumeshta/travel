<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['bravo_tours', 'bravo_spaces', 'bravo_cars', 'bravo_boats', 'bravo_events'] as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'map_google_url')) {
                    $table->text('map_google_url')->nullable()->after('map_zoom');
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['bravo_tours', 'bravo_spaces', 'bravo_cars', 'bravo_boats', 'bravo_events'] as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'map_google_url')) {
                    $table->dropColumn('map_google_url');
                }
            });
        }
    }
};
