<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Coin;

class UpdateCoinsPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updatecoins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Coins Price';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coins = file_get_contents('https://api.coinmarketcap.com/v1/ticker/');
        $coins = json_decode($coins, true);

        foreach ($coins as $coin) {


                $coin_db = Coin::where('identity', $coin['id'])->first();
                if(!$coin_db){
                    $coin_model = New Coin;
                    $coin_model->identity = $coin['id'];
                    $coin_model->name = $coin['name'];
                    $coin_model->symbol = $coin['symbol'];
                    $coin_model->rank = $coin['rank'];
                    $coin_model->price_usd = $coin['price_usd'];
                    $coin_model->last_price_usd = $coin['price_usd'];
                    $coin_model->price_btc = $coin['price_btc'];
                    $coin_model->volume_usd_24h = $coin['24h_volume_usd'];
                    $coin_model->market_cap_usd = $coin['market_cap_usd'];
                    $coin_model->available_supply = $coin['available_supply'];
                    $coin_model->total_supply = $coin['total_supply'];
                    $coin_model->percent_change_1h = $coin['percent_change_1h'];
                    $coin_model->percent_change_24h = $coin['percent_change_24h'];
                    $coin_model->percent_change_7d = $coin['percent_change_7d'];
                    if($coin_model->save()){
                        echo "Coin: ".$coin_model->name." Created\n";
                    }
                }else{
                    $coin_model = Coin::find($coin_db->id);

                    $last_price = $coin_model->price_usd;

                    $coin_model->rank = $coin['rank'];
                    $coin_model->price_usd = $coin['price_usd'];
                    $coin_model->last_price_usd = $last_price;
                    $coin_model->price_btc = $coin['price_btc'];
                    $coin_model->volume_usd_24h = $coin['24h_volume_usd'];
                    $coin_model->market_cap_usd = $coin['market_cap_usd'];
                    $coin_model->available_supply = $coin['available_supply'];
                    $coin_model->total_supply = $coin['total_supply'];
                    $coin_model->percent_change_1h = $coin['percent_change_1h'];
                    $coin_model->percent_change_24h = $coin['percent_change_24h'];
                    $coin_model->percent_change_7d = $coin['percent_change_7d'];
                    if($coin_model->save()){
                        echo "Coin: ".$coin_model->name." Updated\n";
                    }
                }


        }

        $this->info('Coins Updated!');
    }
}
