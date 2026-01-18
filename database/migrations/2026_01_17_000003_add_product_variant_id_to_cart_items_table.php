<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('cart_items', function (Blueprint $table) {
			if (!Schema::hasColumn('cart_items', 'product_variant_id')) {
				$table->unsignedBigInteger('product_variant_id')->nullable()->after('product_id');
				$table->foreign('product_variant_id')
					->references('id')
					->on('product_variants')
					->nullOnDelete();
			}

			// Replace the old unique (user_id, product_id) so a user can add multiple variants.
			// Use a generated key to still prevent duplicate "non-variant" rows.
			if (!Schema::hasColumn('cart_items', 'variant_key')) {
				$table->unsignedBigInteger('variant_key')
					->storedAs('ifnull(`product_variant_id`,0)')
					->after('product_variant_id');
			}

			// Drop old unique index if it exists.
			try {
				$table->dropUnique(['user_id', 'product_id']);
			} catch (Throwable $e) {
				// ignore
			}

			// Enforce one row per (user, product, variant_key)
			try {
				$table->unique(['user_id', 'product_id', 'variant_key']);
			} catch (Throwable $e) {
				// ignore
			}
		});
	}

	public function down(): void
	{
		Schema::table('cart_items', function (Blueprint $table) {
			// Drop new unique first
			try {
				$table->dropUnique(['user_id', 'product_id', 'variant_key']);
			} catch (Throwable $e) {
				// ignore
			}

			if (Schema::hasColumn('cart_items', 'variant_key')) {
				$table->dropColumn('variant_key');
			}

			if (Schema::hasColumn('cart_items', 'product_variant_id')) {
				$table->dropForeign(['product_variant_id']);
				$table->dropColumn('product_variant_id');
			}

			// Restore old unique
			try {
				$table->unique(['user_id', 'product_id']);
			} catch (Throwable $e) {
				// ignore
			}
		});
	}
};
