<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'stock',
        'stock_unlimited',
        'is_active',
    ];

    protected $casts = [
        'stock_unlimited' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function getStockStateAttribute(): string
    {
        if ($this->stock_unlimited) {
            return 'unlimited';
        }

        $qty = (int) ($this->stock ?? 0);
        if ($qty <= 0) {
            return 'out_of_stock';
        }
        if ($qty <= 5) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->price !== null) {
            return (float) $this->price;
        }

        return (float) ($this->product?->price ?? 0);
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return !$this->stock_unlimited && (int) ($this->stock ?? 0) <= 0;
    }

    public function getStockDisplayTextAttribute(): string
    {
        if ($this->stock_unlimited) {
            return 'In stock';
        }

        $qty = (int) ($this->stock ?? 0);
        if ($qty <= 0) {
            return 'Out of stock';
        }
        if ($qty <= 5) {
            return 'Only ' . $qty . ' left';
        }

        return 'In stock (' . $qty . ')';
    }
}
