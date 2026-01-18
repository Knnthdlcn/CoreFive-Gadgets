<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock_unlimited')) {
                $table->boolean('stock_unlimited')->default(false)->after('stock');
            }
            if (!Schema::hasColumn('products', 'stock_updated_at')) {
                $table->timestamp('stock_updated_at')->nullable()->after('stock_unlimited');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock_updated_at')) {
                $table->dropColumn('stock_updated_at');
            }
            if (Schema::hasColumn('products', 'stock_unlimited')) {
                $table->dropColumn('stock_unlimited');
            }
        });
    }
};
