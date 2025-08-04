<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['date', 'client_name', 'client_lastname', 'client_id', 'brand', 'model', 'patent', 'year', 'mileage', 'vehicle_id', 'total', 'number'];
    use HasFactory;
    use SoftDeletes;

    public function PaymentItems()
    {
        return $this->hasMany(PaymentItem::class);
    }

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function Vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
