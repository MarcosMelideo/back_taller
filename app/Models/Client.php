<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['name', 'lastname', 'email', 'phone', 'register_date'];
    use HasFactory;
    use SoftDeletes;

    public function Cars()
    {
        return $this->hasMany(Car::class);
    }

    public function Payments()
    {
        return $this->hasMany(Payments::class);
    }
}
