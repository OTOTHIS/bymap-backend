<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    public function categroies()
    {
        return $this->hasOne(product::class,'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    

    protected $fillable = [
        'name',
        'updated_at',
        'created_at',
        "category_id",
      
    ];
    
}
