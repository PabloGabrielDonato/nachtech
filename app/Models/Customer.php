<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone'];

    public function devices() {
        return $this->hasMany(Device::class);
    }
}