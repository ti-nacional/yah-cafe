<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CoinsExchange;

class UpdateCoinsExchange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:coinsexchange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $response = file_get_contents('https://shapeshift.io/getcoins');

        $coins = json_decode($response, true);

        
        foreach ($coins as $coin) {


                $coin_db = CoinsExchange::where('symbol', $coin['symbol'])->first();

                if(!$coin_db){
                    $coin_model = New CoinsExchange;
                    $coin_model->name = $coin['name'];
                    $coin_model->symbol = $coin['symbol'];
                    $coin_model->image = $coin['image'];
                    $coin_model->imageSmall = $coin['imageSmall'];
                    // $coin_model->minerFee = (isset($coin['minerFee'])): $coin['minerFee'] ? 0 ;

                    if($coin_model->save()){
                        echo "Coin: ".$coin_model->name." Created\n";
                    }
            
                }


        }

        $this->info('Coins Updated!');
    }
}
