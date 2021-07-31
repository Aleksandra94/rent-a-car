<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $table = 'car';
    public $timestamps = false;
    protected $fillable = [
        'registration_licence', 'brand', 'model', 'manufacture_date', 'car_description', 'properties', 'category_id', 'slug'
    ];
}
