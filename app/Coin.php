<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = [
        'id', 'identity', 'name', 'symbol', 'rank', 'price_usd', 'last_price_usd', 'price_btc', 'volume_usd_24h', 'market_cap_usd', 'available_supply', 'total_supply', 'percent_change_1h', 'percent_change_24h', 'percent_change_7d'
    ];
}
