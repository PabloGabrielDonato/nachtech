<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Device extends Model
{
    protected $fillable = [
        'model',
        'imei',
        'status',
        'condition_notes',
        'purchase_price',
        'sale_price',
        'entry_date',
        'sold_at',
        'customer_id'
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'sold_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(function ($device) {
            if ($device->isDirty('status') && $device->status === 'sold' && is_null($device->sold_at)) {
                $device->sold_at = now();
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    // Cálculo automático de Ganancia: Venta - Compra - Reparaciones
    public function profit(): Attribute
    {
        return Attribute::make(
            get: function () {
                $repairCost = $this->repairs->sum('cost');
                if ($this->sale_price) {
                    return $this->sale_price - $this->purchase_price - $repairCost;
                }
                return 0;
            }
        );
    }
}
