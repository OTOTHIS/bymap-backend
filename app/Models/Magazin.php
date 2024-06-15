<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazin extends Model
{
    use HasFactory;
    protected $table = 'magazins' ;
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class , 'magazin_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    protected $fillable = [
        'name', 'Latitude', 'Longitude', "image", 'owner_id',
    ];



}
