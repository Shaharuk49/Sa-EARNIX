<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'base_price',
        'vat_percent',
        'final_admin_price',
        'selling_price',
        'profit_amount',
        'delivery_charge',
        'shop_name',
        'customer_name',
        'customer_phone',
        'district',
        'upazila',
        'delivery_address',
        'additional_instruction',
        'status',
        'profit_status',
    ];

    // Relationship: Order belongs to one User (Customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Order belongs to one Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship: Order has many OrderPayments
    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    // Relationship: Order has many OrderStatusHistories
    public function orderStatusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    // Relationship: Order has many OrderUplineCommissions
    public function orderUplineCommissions()
    {
        return $this->hasMany(OrderUplineCommission::class);
    }
}