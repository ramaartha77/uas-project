<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;
    protected $table = 'markers';

    protected $fillable = [
        'name',
        'image',
        'latitude',
        'longitude',
        'description',
        'address',
        'price',
        'rate'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price' => 'decimal:0',
        'rate' => 'decimal:1'
    ];
}
