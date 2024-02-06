<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'email',
    'document',
    'address',
    'phone',
    'logo',
    'user_id',
    ] ;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'business_id');
    }

    public function banner()
    {
        return $this->hasMany(Banner::class, 'business_id');
    }
}
