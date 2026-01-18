<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_variant_id')) {
                $table->unsignedBigInteger('product_variant_id')->nullable()->after('product_id');
                $table->string('variant_name')->nullable()->after('product_variant_id');

                $table->foreign('product_variant_id')
                    ->references('id')
                    ->on('product_variants')
                    ->nullOnDelete();

                $table->index(['product_id', 'product_variant_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_variant_id')) {
                $table->dropForeign(['product_variant_id']);
                $table->dropIndex(['product_id', 'product_variant_id']);
                $table->dropColumn(['product_variant_id', 'variant_name']);
            }
        });
    }
};
