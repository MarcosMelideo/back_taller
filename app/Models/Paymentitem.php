<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paymentitem extends Model
{
    protected $table = 'payment_items';
    protected $fillable = ['concept', 'description','quantity', 'price_by_unit', 'subtotal', 'payment_id'];
    use HasFactory;
    use SoftDeletes;
    
    public function Payment()
    {
        return $this->belongsTo(Payment::class);
    }

}
