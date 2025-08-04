<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    protected $table = 'diagnoses';
    protected $fillable = ['description', 'date_state', 'brand', 'model', 'vehicle_id', 'client_name', 'client_id'];
    use HasFactory;
    use SoftDeletes;

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function Vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
