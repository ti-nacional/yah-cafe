<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Trade extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'currency', 'value', 'description', 'status_id', 'type'
    ];

    /**
     * The balance of user.
     *
     * @var float
     */
    public static function getBalance($user_id=null, $currency, $type='USD')
	{
		if(!$user_id)
			$user_id = Auth::id();

		$balance = Trade::where('user_id', '=', $user_id)->where('currency', $currency)->where('status_id', '=', '1')->where('type', $type)->sum('value');

		if ($balance < 0) $balance = 0;

		return $balance;
	}

    /**
     * The balance of user.
     *
     * @var float
     */
    public static function getBalanceTrade($user_id=null, $type='USD')
    {
        if(!$user_id)
            $user_id = Auth::id();

        $balance = Trade::where('user_id', $user_id)->where('currency', 'USD')->where('status_id', '1')->where('type', $type)->whereIn('description', array('Add value to Trade', 'Remove value from Trade'))->sum('value');

        if ($balance < 0) $balance = 0;

        return $balance;
    }

	/**
     * The balance of user.
     *
     * @var float
     */
    public static function getTotal($user_id=null, $currency, $type='USD')
	{
		if(!$user_id)
			$user_id = Auth::id();

		$total = Trade::where('user_id', '=', $user_id)->where('currency', $currency)->where('status_id', '=', '1')->where('type', $type)->where('description', 'LIKE', '%Add value to Trade%')->sum('value');

		if ($total < 0) $total = 0;

		return $total;
	}

    public static function getTotalAdd($user_id=null, $type='USD')
    {
        if(!$user_id)
            $user_id = Auth::id();

        $total = Trade::where('user_id', '=', $user_id)->where('currency', 'USD')->where('status_id', '=', '1')->where('type', $type)->where('description', 'LIKE', '%Add value to Trade%')->sum('value');

        return $total;
    }

    public static function getTotalRemove($user_id=null, $type='USD')
    {
        if(!$user_id)
            $user_id = Auth::id();

        $total = Trade::where('user_id', '=', $user_id)->where('currency', 'USD')->where('status_id', '=', '1')->where('type', $type)->where('description', 'LIKE', '%Remove value from Trade%')->sum('value');

        return ltrim($total, '-');
    }

	public static function add(User $user, $value, $value_coin=0, $currency = 'USD', $type='USD'){
    	$debit = New Credit;
    	$debit->user_id = $user->id;
    	$debit->currency = $currency;
    	$debit->value = '-'.$value_coin;
    	$debit->description = 'Deposit value to Trade';

    	if($debit->save()){
	    	$trade = New Trade;
	    	$trade->user_id = $user->id;
	    	$trade->value = $value;
	    	$trade->currency = 'USD';
	    	$trade->description = 'Add value to Trade';
            $trade->type = $type;
	    	$trade->status_id = 1;

	    	if($trade->save()){
	    		return true;
	    	}

	    	$debit->delete();
	    }

    	return false;
    }

    public static function remove(User $user, $value, $value_coin=0, $currency = 'USD', $type='USD'){
        $trade = New Trade;
        $trade->user_id = $user->id;
        $trade->value = '-'.$value;
        $trade->currency = 'USD';
        $trade->description = 'Remove value from Trade';
        $trade->status_id = 1;
        $trade->type = $type;

        if($trade->save()){
            $credit = New Credit;
            $credit->user_id = $user->id;
            $credit->currency = $currency;
            $credit->value = $value_coin;
            $credit->description = 'Remove value from Trade';

            if($credit->save()){
                return true;
            }

            $trade->delete();
        }

        return false;
    }

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
