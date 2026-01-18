<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'admin_user_id',
        'before_quantity',
        'after_quantity',
        'before_unlimited',
        'after_unlimited',
        'note',
    ];

    protected $casts = [
        'before_unlimited' => 'boolean',
        'after_unlimited' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
