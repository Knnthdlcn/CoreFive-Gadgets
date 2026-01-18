<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address_region_code')->nullable()->index();
            $table->string('address_province_code')->nullable()->index();
            $table->string('address_city_code')->nullable()->index();
            $table->string('address_barangay_code')->nullable()->index();
            $table->string('address_street')->nullable();
            $table->string('address_postal_code', 16)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address_region_code',
                'address_province_code',
                'address_city_code',
                'address_barangay_code',
                'address_street',
                'address_postal_code',
            ]);
        });
    }
};
