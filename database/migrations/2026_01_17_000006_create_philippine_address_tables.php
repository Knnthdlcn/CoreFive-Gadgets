<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // In case a previous run failed mid-way, drop partial tables first.
        Schema::dropIfExists('philippine_barangays');
        Schema::dropIfExists('philippine_cities');
        Schema::dropIfExists('philippine_provinces');
        Schema::dropIfExists('philippine_regions');

        Schema::create('philippine_regions', function (Blueprint $table) {
            $table->id();
            // PSGC-style region code (9 digits) from seed file
            $table->string('psgc_code', 9)->unique();
            $table->string('name');
            // Short region code used for cascading selects (e.g., 01, 02, NCR=13)
            $table->string('region_code', 2)->index();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('philippine_provinces', function (Blueprint $table) {
            $table->id();
            // PSGC-style province code (9 digits)
            $table->string('psgc_code', 9)->index();
            $table->string('name');
            // 2-digit region code
            $table->string('region_code', 2)->index();
            // 4-digit province code used by cities/barangays seed data
            $table->string('province_code', 4)->index();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['region_code', 'province_code']);
        });

        Schema::create('philippine_cities', function (Blueprint $table) {
            $table->id();
            // PSGC-style city/municipality code (9 digits)
            $table->string('psgc_code', 9)->index();
            $table->string('name');
            $table->string('region_code', 2)->index();
            // 4-digit province code
            $table->string('province_code', 4)->index();
            // 6-digit city/municipality code used by barangays seed data
            $table->string('city_code', 6)->index();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['province_code', 'city_code']);
        });

        Schema::create('philippine_barangays', function (Blueprint $table) {
            // Seed file inserts explicit numeric IDs, so keep as big integer PK.
            $table->unsignedBigInteger('id')->primary();
            // PSGC-style barangay code (9 digits)
            $table->string('psgc_code', 9)->unique();
            $table->string('name');
            $table->string('region_code', 2)->index();
            $table->string('province_code', 4)->index();
            $table->string('city_code', 6)->index();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('philippine_barangays');
        Schema::dropIfExists('philippine_cities');
        Schema::dropIfExists('philippine_provinces');
        Schema::dropIfExists('philippine_regions');
    }
};
