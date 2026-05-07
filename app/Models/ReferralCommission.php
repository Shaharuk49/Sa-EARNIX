<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'earner_user_id',
        'from_user_id',
        'generation_number',
        'source_type',
        'source_reference_id',
        'base_amount',
        'commission_amount',
        'status',
    ];

    // Relationship: ReferralCommission belongs to one earner user (user who received commission)
    public function earnerUser()
    {
        return $this->belongsTo(User::class, 'earner_user_id');
    }

    // Relationship: ReferralCommission belongs to one from user (user who referred)
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}