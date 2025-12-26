<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $fillable = ['device_id', 'description', 'cost', 'price_charged'];

    public function device() {
        return $this->belongsTo(Device::class);
    }
}