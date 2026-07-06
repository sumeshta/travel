<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'map_google_url')) {
                $table->text('map_google_url')->nullable()->after('map_zoom');
            }
        });

        if (Schema::hasTable('bravo_hotels')) {
            Schema::table('bravo_hotels', function (Blueprint $table) {
                if (!Schema::hasColumn('bravo_hotels', 'map_google_url')) {
                    $table->text('map_google_url')->nullable()->after('map_zoom');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'map_google_url')) {
                $table->dropColumn('map_google_url');
            }
        });

        if (Schema::hasTable('bravo_hotels')) {
            Schema::table('bravo_hotels', function (Blueprint $table) {
                if (Schema::hasColumn('bravo_hotels', 'map_google_url')) {
                    $table->dropColumn('map_google_url');
                }
            });
        }
    }
};
