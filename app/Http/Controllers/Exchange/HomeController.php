<?php

namespace App\Http\Controllers\Exchange;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;
use App\Exchange;
use App\CoinsExchange;
use Validator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
        $coins = CoinsExchange::get();
        $transactions = Cache::remember('transactions', 60, function() { 

        return  Exchange::getTransactions();

         });




        return view('exchange.home',compact('coins','transactions'));
    }
  
    public function makeExchange($from,$to,$amount,$address)
    {
        return  response()->json(['status' => true, 'message' => 'Exchange successfully created now send the quantity so you can complete this transaction','transaction_new'=>Exchange::first()]);

        $rules = array(
            'from' => 'required',
            'to' => 'required',
            'address_to' => 'required',
            'amount_send' => 'required',
            // 'refundAddress' => 'required',

        );
        if ($from && $to && $address && $amount > 0){
          $user = Auth::user();
          $transaction = Exchange::getCreateTransaction($from, $to, $address, $amount, null);

            if($transaction && isset($transaction['status']) && $transaction['status'] == false){
                return  response()->json(['status' => false, 'message' => $transaction['message']]);
            }

            if($transaction){
               
                    $transaction_new = Exchange::create([
                        'user_id' => $user->id,
                        'currency_from' => $from,
                        'amount_from' => $amount,
                        'currency_to' => $to,
                        'amount_to' => 0,
                        'status' => $transaction['status'],
                        'txid_from' => NULL,
                        'txid_to' => NULL,
                        'payin' => $transaction['payinAddress'],
                        'payout' => $transaction['payoutAddress'],
                        'provider_fee' => $transaction['changellyFee'],
                        'east_fee' => $transaction['apiExtraFee'],
                        'id_provider' => $transaction['id'],
                        'provider' => 'changelly'
                    ]);

                    return  response()->json(['status' => true, 'message' => 'Exchange successfully created now send the quantity so you can complete this transaction','transaction_new'=>$transaction_new]);

            }

        }
        return  response()->json(['status' => false, 'message' => 'Not is possible make this exchange, please try again!']);

    }
}
