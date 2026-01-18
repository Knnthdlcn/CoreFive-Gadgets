<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_stock_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('admin_user_id');

            $table->integer('before_quantity')->nullable();
            $table->integer('after_quantity')->nullable();
            $table->boolean('before_unlimited')->default(false);
            $table->boolean('after_unlimited')->default(false);

            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('admin_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_stock_audits');
    }
};
