<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = ['id'];
    
    use HasFactory;

    public function Stock()
    {
       return $this->hasMany(Stock::class);
       //これいるのか？なくても動いた
    }       
    
}
