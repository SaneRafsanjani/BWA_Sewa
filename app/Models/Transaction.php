<?php

namespace App\Models;


use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'trx_id',
        'proof',
        'phone_number',
        'address',
        'total_amount',
        'product_id',
        'store_id',
        'duration',
        'is_paid',
        'delivery_type',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'total_amount'=> MoneyCast::class,
        'started_at' => 'date',
        'ended_at'   => 'date'
    ];

    public static function generateUniqueTrxId()
    {
        $prefix = 'SLBWA';
        do {
            $randomString = $prefix . mt_rand(1000,9999); // prefix + generate angka random
        } while (self::where('trx_id', $randomString)->exists());

        return $randomString;
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
