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
        Schema::table('products', function (Blueprint $table) {
            // Add image column if it doesn't exist
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('image_path');
            }
            
            // Add name column if it doesn't exist (for backward compatibility)
            if (!Schema::hasColumn('products', 'name')) {
                $table->string('name')->nullable()->after('product_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('products', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
