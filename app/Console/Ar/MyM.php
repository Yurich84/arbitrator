<?php

namespace App\Console\Ar;

use App\Console\Commands\My;
use Carbon\Carbon;
use ccxt\ccex;
use ccxt\Exchange;
use JakubOnderka\PhpConsoleColor\ConsoleColor;
use SebastianBergmann\Environment\Console;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use \DB;


class MyM extends My
{
    public function __construct()
    {
        parent::__construct();
        $this->output = new ConsoleOutput();
    }

    public $exchange_namespace = '\\ccxt\\';

    /**
     * Тестовий метод
     */
    public function index()
    {
        $this->info('This is Index method');
        $this->line('line');
        $this->comment('comment');
        $this->question('question');
        $this->error('error');
    }


    public function updateExchangesInDB()
    {
        $exchanges = \App\Models\Stock::all();

        foreach ($exchanges as $exchange_model) {
            $exchange = $this->exchange_namespace . $exchange_model->ccxt_id;
            $exchange = new $exchange ();

            DB::table('stocks')
                ->where('id', $exchange_model->id)
                ->update([
                'logo'       => @$exchange->urls['logo'] ?: NULL,
            ]);
        }

    }


    public function test($id)
    {
        $exchange = $this->exchange_namespace . $id;
        $exchange = new $exchange ();

        $s = $exchange->fetchMarkets();

        dd(collect($s)->where('active', false));

    }

}