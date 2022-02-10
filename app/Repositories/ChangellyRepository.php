<?php 
namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
// use App\Order;
use App\User;
use DB;

class ChangellyRepository
{
  public static $config = [
    'secret_key' => '8933020c975948d2b78f0a1d726f0f72',
    'password' => 'Forcount@4512',
  ];


  public static function getBitsendStatus($bitsend_id)
  {
    $postData = array(
      'name' => 'Forcount',
      'secret_key' => ChangellyRepository::$config['secret_key'],
      'password' => md5(ChangellyRepository::$config['password']),
      'bitsend_id' => $bitsend_id,
    );

    // Setup cURL
    $ch = curl_init('https://www.alfacoins.com/api/bitsend_status');
    curl_setopt_array($ch, array(
      CURLOPT_POST => TRUE,
      CURLOPT_SSL_VERIFYPEER => TRUE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
      CURLOPT_POSTFIELDS => json_encode($postData)
    ));

    // Send the request
    $response = curl_exec($ch);
    $api_data = json_decode($response);

    return $api_data;
  }
}