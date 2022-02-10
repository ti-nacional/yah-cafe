<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TransactionUsd;
use Illuminate\Support\Facades\Mail;
use App\Wallet;
use App\User;
use App\Mail\ExchangeComplete;
use App\Mail\ExchangePeding;
use Carbon\Carbon;

class Exchange extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'id', 
    	'user_id', 
    	'type', 
    	'currency_from',
    	'amount_from',
    	'currency_to',
    	'amount_to', 
    	'status', 
    	'txid_from',
    	'txid_to',
    	'payin',
    	'payout',
    	'provider_fee',
    	'east_fee',
    	'id_provider',
    	'txid_to',
    	'txid_from',
        'provider' // changelly | shapeshift
    ];

    public static function apiData()
    {
    	$api['key'] = '8ff93d7e0afb4e0883d0ca0c19cfcac8';
    	$api['secret'] = '0b189b6cd1d6419c19464a7c9d80838365bade4902bebdc6d52d72a5c97f5104';

    	return $api;
    }

    public static function getCurrencies(){

        $response = file_get_contents('https://shapeshift.io/getcoins');



    	$data = json_decode($response, true);

    	return $data;
    }

    public static function getMin($from, $to){

    	$apiKey = Exchange::apiData()['key'];
    	$apiSecret = Exchange::apiData()['secret'];
    	$apiUrl = 'https://api.changelly.com';
    	$message = json_encode(
    		array('jsonrpc' => '2.0', 'id' => 1, 'method' => 'getMinAmount', 'params' => array('from' => $from, 'to' => $to))
    	);
    	$sign = hash_hmac('sha512', $message, $apiSecret);
    	$requestHeaders = [
    		'api-key:' . $apiKey,
    		'sign:' . $sign,
    		'Content-type: application/json'
    	];
    	$ch = curl_init($apiUrl);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    	$response = curl_exec($ch);
    	curl_close($ch);

    	$data = json_decode($response, true);

    	if($data && isset($data['result'])){
    		return $data['result'];
    	}

    	return 0;
    }

    public static function getExchangeAmount($from, $to, $amount){

    	$apiKey = Exchange::apiData()['key'];
    	$apiSecret = Exchange::apiData()['secret'];
    	$apiUrl = 'https://api.changelly.com';
    	$message = json_encode(
    		array('jsonrpc' => '2.0', 'id' => 1, 'method' => 'getExchangeAmount', 'params' => array('from' => $from, 'to' => $to, 'amount' => $amount))
    	);
    	$sign = hash_hmac('sha512', $message, $apiSecret);
    	$requestHeaders = [
    		'api-key:' . $apiKey,
    		'sign:' . $sign,
    		'Content-type: application/json'
    	];
    	$ch = curl_init($apiUrl);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    	$response = curl_exec($ch);
    	curl_close($ch);

    	$data = json_decode($response, true);

    	if($data && isset($data['result'])){
    		return $data['result'];
    	}

    	return 0;
    }

    public static function getGenerateAddress($from, $to, $address_to){

    	$apiKey = Exchange::apiData()['key'];
    	$apiSecret = Exchange::apiData()['secret'];
    	$apiUrl = 'https://api.changelly.com';
    	$message = json_encode(
    		array('jsonrpc' => '2.0', 'id' => 1, 'method' => 'generateAddress', 'params' => array('from' => $from, 'to' => $to, 'address' => $address_to))
    	);
    	$sign = hash_hmac('sha512', $message, $apiSecret);
    	$requestHeaders = [
    		'api-key:' . $apiKey,
    		'sign:' . $sign,
    		'Content-type: application/json'
    	];
    	$ch = curl_init($apiUrl);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    	$response = curl_exec($ch);
    	curl_close($ch);

    	$data = json_decode($response, true);

    	if($data && isset($data['result'])){
    		return $data['result'];
    	}

    	return 0;
    }

    public static function getCreateTransaction($from, $to, $address_to, $amount_send, $refundAddress){

    	$apiKey = Exchange::apiData()['key'];
    	$apiSecret = Exchange::apiData()['secret'];
    	$apiUrl = 'https://api.changelly.com';
    	$message = json_encode(
    		array(
    			'jsonrpc' => '2.0', 
    			'id' => 1, 
    			'method' => 'createTransaction', 
    			'params' => array(
    				'from' => $from, 
    				'to' => $to, 
    				'address' => $address_to,
    				'amount' => $amount_send,
    				'refundAddress' => $refundAddress,
    			)
    		)
    	);
    	$sign = hash_hmac('sha512', $message, $apiSecret);
    	$requestHeaders = [
    		'api-key:' . $apiKey,
    		'sign:' . $sign,
    		'Content-type: application/json'
    	];
    	$ch = curl_init($apiUrl);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    	$response = curl_exec($ch);
    	curl_close($ch);

    	$data = json_decode($response, true);
    	if($data && isset($data['result'])){
    		return $data['result'];
    	}elseif($data && isset($data['error'])){
    		return ['status' => false, 'message' => $data['error']['message']];
    	}

    	return NULL;
    }

    public static function createExchange(User $user, $from_coin, $to_coin, $amount){

    	if($from_coin == 'btc'){
    		$wallet_from = $user->walletBTC;
    		$address_from = $wallet_from->address;
    		$balance_from = $wallet_from->checkBalance($wallet_from);
    	}elseif($from_coin == 'eth'){
    		$wallet_from = $user->walletETH;
    		$address_from = $wallet_from->address;
    		$balance_from = $wallet_from->balance($wallet_from);
    	}elseif($from_coin == 'ltc'){
    		$wallet_from = $user->walletLTC;
    		$address_from = $wallet_from->address;
    		$balance_from = $wallet_from->balance($wallet_from)['available'];

    	}elseif($from_coin == 'dash'){
          $wallet_from = Wallet::where('coin', 'dash')->where('user_id', $user->id)->where('status', 1)->first();
          $wallet_dash = New Wallet;
          $balance_from = $wallet_dash->balance($user, 'dash');
          $address_from = $walletDash->address;
    	}elseif($from_coin == 'bch'){
    	  $wallet_from = Wallet::where('coin', 'bch')->where('user_id', $user->id)->where('status', 1)->first();
          $wallet_bch = New Wallet;
          $balance_from = $wallet_bch->balance($user, 'bch');
          $address_from = $walletDash->address;
    	}elseif($from_coin == 'zec'){
    		$wallet_from = $user->walletZEC;
    		$address_from = $wallet_from->address;
    		$balance_from = $wallet_from->checkBalance($wallet_from);
    	}elseif($from_coin == 'btg'){
    		$wallet_from = $user->walletBTG;
    		$address_from = $wallet_from->address;
    		$balance_from = $wallet_from->checkBalance($wallet_from);
    	}else{
    		return false;
    	}
    	if($to_coin == 'btc'){
    		$address_to = $user->walletBTC->address;
    	}elseif($to_coin == 'eth'){
    		$address_to = $user->walletETH->address;
    	}elseif($to_coin == 'ltc'){
    		$address_to = $user->walletLTC->address;
    	}elseif($to_coin == 'dash'){
    		$address_to = $user->walletDash->address;
    	}elseif($to_coin == 'bch'){
    		$address_to = $user->walletBCH->address;
    	}elseif($to_coin == 'zec'){
    		$address_to = $user->walletZEC->address;
    	}elseif($to_coin == 'btg'){
    		$address_to = $user->walletBTG->address;
    	}else{
    		return false;
    	}	
		// Check Balance

    	if($amount > $balance_from) return ['status' => false, 'message' => 'You not have balance to make this transactions!'];

		// Create address to payin

    	$transaction = Exchange::getCreateTransaction($from_coin, $to_coin, $address_to, $amount, $address_from);
    	if($transaction && isset($transaction['status']) && $transaction['status'] == false){
    		return ['status' => false, 'message' => $transaction['message']];
    	}
    	if($transaction){
    		// Make Transaction
    		if($from_coin == 'usdt'){
    			$txid_from = $wallet_from->sendUsd($user, $amount, $transaction['payinAddress']);
    		}else{
    			$txid_from = $wallet_from->createTransaction($wallet_from->address, $transaction['payinAddress'], $amount);
    		}	
    		if($txid_from && isset($txid_from['status']) && $txid_from['status'] == true){
    			$transaction_new = Exchange::create([
    				'user_id' => $user->id,
    				'currency_from' => $from_coin,
    				'amount_from' => $amount,
    				'currency_to' => $to_coin,
    				'amount_to' => 0,
    				'status' => $transaction['status'],
    				'txid_from' => $txid_from['id'],
    				'txid_to' => NULL,
    				'payin' => $transaction['payinAddress'],
    				'payout' => $transaction['payoutAddress'],
    				'provider_fee' => $transaction['changellyFee'],
    				'east_fee' => $transaction['apiExtraFee'],
    				'id_provider' => $transaction['id'],
    				'provider' => 'changelly'
    			]);

    			return ['status' => true, 'message' => 'Exchange complete with success, wait the process finish to get your coins.'];
    		}
    	}

    	return ['status' => false, 'message' => 'Not possible create your exchange order. Try again!'];

    }	

    public static function getTransactions(){

    	$apiKey = Exchange::apiData()['key'];
    	$apiSecret = Exchange::apiData()['secret'];
    	$apiUrl = 'https://api.changelly.com';
    	$message = json_encode(
    		array(
    			'jsonrpc' => '2.0', 
    			'id' => 1, 
    			'method' => 'getTransactions', 
    			'params' => array()
    		)
    	);
    	$sign = hash_hmac('sha512', $message, $apiSecret);
    	$requestHeaders = [
    		'api-key:' . $apiKey,
    		'sign:' . $sign,
    		'Content-type: application/json'
    	];
    	$ch = curl_init($apiUrl);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    	$response = curl_exec($ch);
    	curl_close($ch);

    	$data = json_decode($response, true);

    	if($data && isset($data['result'])){
    		return $data['result'];
    	}

    	return NULL;
    }

    public static function getStatus($id_provider){

    	$apiKey = Exchange::apiData()['key'];
    	$apiSecret = Exchange::apiData()['secret'];
    	$apiUrl = 'https://api.changelly.com';
    	$message = json_encode(
    		array(
    			'jsonrpc' => '2.0', 
    			'id' => 1, 
    			'method' => 'getStatus', 
    			'params' => array(
    				'id' => $id_provider,
    			)
    		)
    	);
    	$sign = hash_hmac('sha512', $message, $apiSecret);
    	$requestHeaders = [
    		'api-key:' . $apiKey,
    		'sign:' . $sign,
    		'Content-type: application/json'
    	];
    	$ch = curl_init($apiUrl);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

    	$response = curl_exec($ch);
    	curl_close($ch);

    	$data = json_decode($response, true);

    	if($data && isset($data['result'])){
    		return $data['result'];
    	}

    	return NULL;
    }

    public static function check(Exchange $exchange){

    	$check_status = Exchange::getStatus($exchange->id_provider);

    	if($check_status){
    		$exchange->status = $check_status;
    		$exchange->save();
    	}

    	if($check_status == 'finished'){
    		$transactions = Exchange::getTransactions();

    		foreach ($transactions as $transaction) {
    			if($transaction['id'] == $exchange->id_provider){
    				$exchange->status = $transaction['status'];

    				if($transaction['status'] == 'finished'){
    					$exchange->txid_to = $transaction['payoutHash'];
    					$exchange->amount_to = $transaction['amountTo'];
    				}

    				$exchange->save();

    				if($transaction['status'] == 'finished'){
    					if($exchange->currency_to == 'usdt'){
    						$txusd = New TransactionUsd;
    						$txusd->createTransaction(User::find($exchange->user_id), $transaction['amountTo'], 'Receive from Exchange with '.strtoupper($exchange->currency_from), $transaction['payoutHash']);
    					}
    					$user = User::where('id',$exchange->user_id)->first();
    					$order['from'] = $exchange->currency_from;
    					$order['to'] = $exchange->currency_from;
    					$order['from_amount'] = bcdiv( $exchange->amount_from,1,6);
    					$order['to_amount'] =  bcdiv($exchange->amount_to,1,6);
    					$order['date'] = Carbon::now()->toDayDateTimeString();
    					Mail::to($user)->send(new ExchangeComplete($user,$order));
    				}
    			}
    		}

    	}

    	return true;

    }
}
