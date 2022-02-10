<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradeHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'type', 'gains', 'currency_from', 'currency_to', 'amount_from', 'amount_to', 'price_from', 'price_to', 'status'
    ];

    protected $table = 'trades_history';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
