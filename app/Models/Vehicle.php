<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    protected $table = 'vehicles';
    protected $fillable = ['brand', 'model', 'year', 'petent', 'mileage', 'client_name', 'client_id', 'register_date'];
    use HasFactory;
    use SoftDeletes;

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function Diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function Payments()
    {
        return $this->hasMany(Payments::class);
    }
}
