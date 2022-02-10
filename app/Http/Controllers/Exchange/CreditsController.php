<?php

namespace App\Http\Controllers\Exchange;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Credit;
use App\Coin;
use Illuminate\Support\Facades\Auth;

class CreditsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	$balance = [];
    	foreach (Coin::get() as $coin) {
    		$balance[$coin->symbol] = bcdiv(Credit::where('currency',$coin->symbol)->where('user_id',Auth::id())->where('description','LIKE','%Deposit%')->sum('value'), 1, 6);
    	}


        return view('exchange/credits.index', compact('balance'));
    }

    public function extract($coin = 'BTC')
    {       
        $total['deposited'] = Credit::where('currency',$coin)->where('user_id',Auth::id())->where('description','LIKE','%Deposit%')->sum('value');
        $credits = Credit::where('currency',$coin)->where('user_id',Auth::id())->get();
        return view('exchange/credits.extract', compact('coin','credits','total'));
    }
   

    public function withdrawal($coin = 'BTC')
    {       
       return redirect()->back()->with('danger', 'Invalid two-step authentication code. Please try again!');
    }
}
