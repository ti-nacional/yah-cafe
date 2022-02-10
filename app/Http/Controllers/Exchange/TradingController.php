<?php

namespace App\Http\Controllers\Exchange;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\TradeHistory;
use App\Coin;
use App\User;
use App\Trade;
use Carbon\Carbon;
use App\Credit;

class TradingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($type='USD')
    {
    	$user = Auth::user();

    	$total['deposited'] = Credit::where('description', 'LIKE', 'Deposit to Robot - '.$type)->where('user_id', $user->id)->sum('value');
    	$total['withdrawal'] = Credit::where('description', 'LIKE', 'Remove from Robot - '.$type)->where('user_id', $user->id)->sum('value');

            $gains_all['TUSD'] = TradeHistory::where('type','TUSD')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') + 29300;
            $gains_all['USDT'] = TradeHistory::where('type','USDT')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') + 32940;
            $gains_all['BTC'] = TradeHistory::where('type','BTC')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') + 9.32746;
            $gains_all['ETH'] = TradeHistory::where('type','ETH')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') + 20.32746;
            $gains_all['DASH'] = TradeHistory::where('type','DASH')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains');
            $gains_all['XRP'] = TradeHistory::where('type','XRP')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') + 42462;
        
            $gains['TUSD'] = TradeHistory::where('type','TUSD')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains');
            $gains['USDT'] = TradeHistory::where('type','USDT')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') ;
            $gains['BTC'] = TradeHistory::where('type','BTC')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') ;
            $gains['ETH'] = TradeHistory::where('type','ETH')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains');
            $gains['DASH'] = TradeHistory::where('type','DASH')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains');
            $gains['XRP'] = TradeHistory::where('type','XRP')->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains') ;

        return view('exchange/trading.index', compact('type', 'total', 'gains','gains_all'));
    }
    public function history($type='USD',$user_id){

    	$orders = TradeHistory::where('user_id', $user_id)->orderBy('created_at', 'DESC')->where('amount_from', '>', 0)->where('amount_to', '>', 0)->limit(10)->where('type', $type)->where('status', 1)->get();
    	return $orders;

    }

    public function robot($type='USD',$user_id){
        $user = User::find($user_id);
        
        if($type == 'USD'){
            $random_number = rand (0, 5);
            $random_coin = ['TUSD','USDT','BTC','ETH','DASH','XRP'];

            $type = $random_coin[$random_number];
        }
        if($user){


            $check_debit = Trade::where('user_id', $user->id)->where('type', $type)->orderBy('id', 'DESC')->first();
            if($check_debit && $check_debit->value < 0){
                $check_debit->delete();
            }

            $porc_rand = random_int(1, 61) / 100;

            $balances['USD'] = bcdiv(Trade::getBalance($user->id, 'USD', $type) + 10, 1, 6);
            $balances['BTC'] = bcdiv(Trade::getBalance($user->id, 'BTC', $type) + 10, 1, 6);
            $balances['ETH'] = bcdiv(Trade::getBalance($user->id, 'ETH', $type) + 10, 1, 6);
            $balances['LTC'] = bcdiv(Trade::getBalance($user->id, 'LTC', $type) + 10, 1, 6);
            $balances['BCH'] = bcdiv(Trade::getBalance($user->id, 'BCH', $type) + 10, 1, 6);
            $balances['DASH'] = bcdiv(Trade::getBalance($user->id, 'DASH', $type) + 10, 1, 6);
            $balances['XEM'] = bcdiv(Trade::getBalance($user->id, 'XEM', $type) + 10, 1, 6);
            $balances['NEO'] = bcdiv(Trade::getBalance($user->id, 'NEO', $type) + 10, 1, 6);
            $balances['XMR'] = bcdiv(Trade::getBalance($user->id, 'XMR', $type) + 10, 1, 6);
            $balances['ETC'] = bcdiv(Trade::getBalance($user->id, 'ETC', $type) + 10, 1, 6);
            $balances['XRP'] = bcdiv(Trade::getBalance($user->id, 'XRP', $type) + 2000, 1, 6);
            $balances['TUSD'] = bcdiv(Trade::getBalance($user->id, 'TUSD', $type) + 2000, 1, 6);
            $balances['USDT'] = bcdiv(Trade::getBalance($user->id, 'USDT', $type) + 2000, 1, 6);

            $with_balance = [];
            $no_balance = [];
            foreach ($balances as $key => $balance) {
                if($balance > 0){
                    $with_balance[] = $key;
                }else{
                    $no_balance[] = $key;
                }
            }

            foreach ($balances as $key => $balance) {
                $coin_rand_all = $no_balance;

                $random_number = rand (0, 12);
                $random_coin = ['USD','BTC','ETH','LTC','BCH','DASH','XEM','NEO','XMR','ETC','XRP','TUSD','USDT'];
                $coin_rand = $random_coin[$random_number];

                $coin = Coin::where('symbol', ($key == 'USD') ? 'TUSD' : $key)->first();
                // dd($key);
                 $to = Coin::where('symbol', $coin_rand)->first();
                // do {
                //     $to = Coin::where('symbol', $coin_rand)->first();
                // } while ($to->symbol == $key);

                $to_trade = $balance;

                // echo "###################<br />";
                // echo "From Coin: ".$key." (".$coin->price_usd.")<br />";
                // echo "Balance Coin: ".$balance."<br />";
                // echo "To Coin: ".$coin_rand." (".$to->price_usd.")<br />";

                if($balance > 0){
	            	$price_to = $to->price_usd;

	            	if($key == 'USD' || $coin_rand == 'USD' || $coin->symbol == 'USD'){
	            		$price = ($coin->symbol == 'USD') ? 1 : $to->price_usd;
	            		$amount_to = $balance / bcdiv($price, 1, 4);
	            	}else{
	            		$amount_to = $balance * bcdiv($coin->price_usd, 1, 4);
	            		$amount_to = $amount_to / bcdiv($to->price_usd, 1, 4);
	            	}

                    $amount_to_convert = bcdiv($amount_to, 1, 6);

	            	if($amount_to_convert > 0){

		            	$trade_sell = New Trade;
		            	$trade_sell->user_id = $user->id;
		            	$trade_sell->currency = $key;
		            	$trade_sell->value = '-'.$balance;
		            	$trade_sell->description = 'Sell - Trade History';
		            	$trade_sell->type = $type;
		            	$trade_sell->status_id = 1;
		            	$trade_sell->save();

		            	// echo "Amount Coin: ".$amount_to."<br />";

		            	$trade_buy = New Trade;
		            	$trade_buy->user_id = $user->id;
		            	$trade_buy->currency = $to->symbol;
		            	$trade_buy->value = $amount_to_convert;
		            	$trade_buy->description = 'Buy - Trade History';
		            	$trade_buy->type = $type;
		            	$trade_buy->status_id = 1;
		            	$trade_buy->save();

		            	$trade_history = New TradeHistory;
		            	$trade_history->user_id = $user->id;
		            	$trade_history->type = $type;
		            	$trade_history->currency_from = $coin->symbol;
		            	$trade_history->currency_to = $coin_rand;
		            	$trade_history->amount_from = $balance;
		            	$trade_history->amount_to = $amount_to_convert;
		            	$trade_history->price_from = $coin->price_usd;
		            	$trade_history->price_to = $to->price_usd;
                        $trade_history->status = 1;
		            	$trade_history->gains = bcdiv(rand (99999999, 1000000000)  / 1000000000000,1,15);
		            	$trade_history->save();
                        $trade_history->today = TradeHistory::where('type',$type)->where('created_at', 'LIKE', '%'.date('Y-m-d').'%')->sum('gains');
                        if($user->id == 3 || $user->id == 2){
                            if($type == 'TUSD')$trade_history->all = TradeHistory::where('type',$type)->sum('gains') + 29300;

                            if($type == 'USDT')$trade_history->all = TradeHistory::where('type',$type)->sum('gains') +32940;

                            if($type == 'BTC')$trade_history->all = TradeHistory::where('type',$type)->sum('gains') + 9.32746;
                            if($type == 'ETH')$trade_history->all = TradeHistory::where('type',$type)->sum('gains') + 20.32746;

                            if($type == 'DASH')$trade_history->all = TradeHistory::where('type',$type)->sum('gains') ;

                            if($type == 'XRP')$trade_history->all = TradeHistory::where('type',$type)->sum('gains')+ 42462;
                        }else{
                            $trade_history->all = TradeHistory::where('type',$type)->sum('gains') ;
                        }



                        return response()->json($trade_history);
		                // echo "Create Order to User #".$user->id."<br />\n";

		            } 
	            }
            }


            die;

            foreach (Coin::orderBy('id', 'asc')->limit(12)->get() as $coin) {

                // echo "###################<br />";
                // echo "Start Coin: ".$coin->symbol."<br />";

                $user_trade = Trade::getBalanceTrade($user->id, $type);

                // echo "Balance Trade (USD): ".$user_trade."<br />";

                $balance_coin = Trade::getBalance($user->id, $coin->symbol, $type);
                // echo "Balance Coin: ".$balance_coin."<br />";

                $coin_rand_all = array("BTC", "ETH", "XRP", "BCH", "LTC", "DASH", "XEM", "NEO", "MIOTA", "XMR", "ETC");
                $coin_rand = $coin_rand_all[array_rand($coin_rand_all)];
                $coin_rand = ($coin_rand != $coin->symbol) ? $coin_rand : $coin_rand_all[array_rand($coin_rand_all)];
                // echo "Coin Rand: ".$coin_rand."<br />";

                // echo "Coin Price: ".$coin->price_usd."<br />";
                // echo "Coin Last Price: ".$coin->last_price_usd."<br />";

                // echo "<br />** Condition 2 = Price < Last<br />";
                $coin_sell_rand = array("USD", $coin_rand);
                $coin_sell_rand = $coin_sell_rand[array_rand($coin_sell_rand)];
                // echo "Coin Sell Rand: ".$coin_sell_rand."<br />";

                if($balance_coin > 0 && $coin_sell_rand != $coin->symbol){

                	$to = Coin::where('symbol', $coin_sell_rand)->first();
                	$price_to = $to->price_usd;

                	$trade_sell = New Trade;
                	$trade_sell->user_id = $user->id;
                	$trade_sell->currency = $coin->symbol;
                	$trade_sell->value = '-'.$balance_coin;
                	$trade_sell->description = 'Sell - Trade History';
                	$trade_sell->type = $type;
                	$trade_sell->status_id = 1;
                	$trade_sell->save();

                	if($coin_sell_rand == 'USD' || $coin->symbol == 'USD'){
                		$amount_to = $balance_coin / $to->price_usd;
                	}else{
                		$amount_to = $balance_coin / $coin->price_usd;
                		$amount_to = $amount_to * $to->price_usd;
                	}

                	$trade_buy = New Trade;
                	$trade_buy->user_id = $user->id;
                	$trade_buy->currency = $to->symbol;
                	$trade_buy->value = $amount_to;
                	$trade_buy->description = 'Buy - Trade History';
                	$trade_buy->type = $type;
                	$trade_buy->status_id = 1;
                	$trade_buy->save();

                	$trade_history = New TradeHistory;
                	$trade_history->user_id = $user->id;
                	$trade_history->type = $type;
                	$trade_history->currency_from = $coin->symbol;
                	$trade_history->currency_to = $coin_sell_rand;
                	$trade_history->price_from = $coin->price_usd;
                	$trade_history->price_to = $to->price_usd;
                	$trade_history->status = 1;
                	$trade_history->save();

                    // echo "Create Order to User #".$user->id."<br />\n";
                }
                // echo "<br />";

                // echo "###################<br /><br />";
            }


            $date_actual = Carbon::now()->format('Y-m-d');
            // $old_robots = TradeHistory::where('description', 'NOT LIKE', '%Add value to Trade%')->where('created_at', 'NOT LIKE', '%'.$date_actual.'%');
        }else{
            return "Nenhum usuário possui o robo!";
        }
    }
     public function prices(){
        
        foreach (Coin::get() as $coin) {
          $data[$coin->symbol] = $coin;
        }

        return response()->json($data);

    }
}
