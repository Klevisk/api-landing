<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'description',
        'business_id',
    ];
 public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }


}
