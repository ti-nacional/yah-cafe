<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Credit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'currency', 'value', 'description', 'status'
    ];

    /**
     * The balance of user.
     *
     * @var float
     */
    public static function getBalance($user_id=null, $currency)
	{
		if(!$user_id)
			$user_id = Auth::id();

		$balance = Credit::where('user_id', '=', $user_id)->where('currency', $currency)->where('status', '=', '1')->sum('value');

		if ($balance < 0) $balance = 0;

		return $balance;
	}

     /**
     * The balance of user.
     *
     * @var float
     */
    public static function getTotalReceive($user_id=null, $currency)
    {
        if(!$user_id)
            $user_id = Auth::id();

        $balance = Credit::where('user_id', '=', $user_id)->where('currency', $currency)->where('status', '=', '1')->where('value', 'NOT LIKE', '%-%')->sum('value');

        if ($balance < 0) $balance = 0;

        return $balance;
    }

     /**
     * The balance of user.
     *
     * @var float
     */
    public static function getTotalWithdrawal($user_id=null, $currency)
    {
        if(!$user_id)
            $user_id = Auth::id();

        $balance = Credit::where('user_id', '=', $user_id)->where('currency', $currency)->where('status', '=', '1')->where('value', 'LIKE', '%-%')->sum('value');

        return ltrim($balance, '-');
    }

    /**
     * The balance of users.
     *
     * @var float
     */
    public static function getBalanceUsers($currency)
    {
        $balance = Credit::where('currency', $currency)->where('status', '=', '1')->sum('value');

        if ($balance < 0) $balance = 0;

        return $balance;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deposit(){
        return $this->hasOne('App\Deposit');
    }

    public function withdrawal(){
        return $this->hasOne('App\Withdrawal');
    }
}
