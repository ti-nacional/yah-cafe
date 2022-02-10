<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinsExchange extends Model
{
    protected $fillable = [
    	'id',  'symbol', 'image', 'imageSmall', 'name', 'minerFee'
    ];
}
