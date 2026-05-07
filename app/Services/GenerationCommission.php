<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerationCommission extends Model
{
    protected $fillable = ['generation', 'amount'];

    /**
     * Get all 24 generation rates as [generation => amount] array.
     */
    public static function getRates(): array
    {
        return static::orderBy('generation')
            ->pluck('amount', 'generation')
            ->toArray();
    }

    /**
     * Seed default rates: 220 Tk split across 24 generations.
     * Default: Gen1=50, Gen2=30, Gen3=20, Gen4-10=10each, Gen11-24=~1each
     */
    public static function seedDefaults(): void
    {
        $defaults = [
            1 => 50, 2 => 30, 3 => 20, 4 => 15, 5 => 12,
            6 => 10, 7 => 8,  8 => 7,  9 => 6,  10 => 5,
            11 => 4, 12 => 4, 13 => 3, 14 => 3, 15 => 3,
            16 => 2, 17 => 2, 18 => 2, 19 => 2, 20 => 2,
            21 => 1, 22 => 1, 23 => 1, 24 => 1,
        ];

        foreach ($defaults as $gen => $amount) {
            static::updateOrCreate(['generation' => $gen], ['amount' => $amount]);
        }
    }
}