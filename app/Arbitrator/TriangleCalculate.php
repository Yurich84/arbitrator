<?php

namespace App\Arbitrator;

use App\Models\Rate;

class TriangleCalculate
{
    const MY_MAX_LIMIT = 20; // USD, если больше - тогруем на єту сумму
    const MY_MIN_LIMIT = 0; // USD, если меньше - не тогруем

    public function __construct($symbol, $pairs, $tax)
    {
        $this->symbol = $symbol;
        $this->pairs = $pairs;
        $this->tax = $tax;

        $this->getProfit();
    }

    protected $symbol;
    protected $pairs;
    protected $tax;

    public $profit;
    public $min = 0;
    public $comment = '';
    public $error = null;


    /**
     * Считаем профит
     */
    public function getProfit()
    {
        list($A, $B, $C, $A) = explode('->', $this->symbol);

        /*
         * A->B->C->A
         */
        $order_A_B = self::getOrder($A, $B, $this->pairs);
        $order_B_C = self::getOrder($B, $C, $this->pairs);
        $order_C_A = self::getOrder($C, $A, $this->pairs);

        $this->profit = 100 * $order_A_B->price * $order_B_C->price * $order_C_A->price * pow($this->tax, 3) - 100;

        if( $order_A_B->min * $order_B_C->min * $order_C_A->min > 0 ) {

            // находим минимум
            $this->getMin($order_A_B, $order_B_C, $order_C_A);

            // симулирование торгов
            $this->simulateTrade($order_A_B, $order_B_C, $order_C_A, $A, $B, $C);
        }
    }

    /**
     * получаем цену
     * @param $from
     * @param $to
     * @param $pairs_arr
     * @return object
     */
    protected static function getOrder($from, $to, $pairs_arr)
    {
        $order = (object) [
            'base_curr' => $from, 'quote_curr' => $to, 'price' => 0, 'market_price' => 0, 'direction' => null, 'min' => 0,
        ];
        foreach ($pairs_arr as $pair) {
            if ($pair->base_curr == $from && $pair->quote_curr == $to) {
                // SELL: ми продаєм, берем bid
                if($pair->bid > 0) {
                    $order = (object) [
                        'base_curr'    => $from,
                        'quote_curr'   => $to,
                        'price'        => $pair->bid,
                        'market_price' => $pair->bid,
                        'direction'    => 'sell',
                        'min'          => $pair->min_bid,
                    ];
                }
            } elseif ($pair->base_curr == $to && $pair->quote_curr == $from) {
                // BUY: ми купуємо, берем ask
                if($pair->ask > 0) {

                    $order = (object) [
                        'base_curr'    => $to,
                        'quote_curr'   => $from,
                        'price'        => 1 / $pair->ask,
                        'market_price' => $pair->ask,
                        'direction'    => 'buy',
                        'min'          => $pair->min_ask
                    ];
                }
            }
        }

        return $order;

    }


    private function getMin($order_A_B, $order_B_C, $order_C_A)
    {
        $error = null;
        // стоимость валюти от которой торгуем в долларах
        $rate_AB_base = Rate::where('symbol', $order_A_B->base_curr)->first();
        $rate_BC_base = Rate::where('symbol', $order_B_C->base_curr)->first();
        $rate_CA_base = Rate::where('symbol', $order_C_A->base_curr)->first();

        if( $rate_AB_base && $rate_BC_base && $rate_CA_base ) {
            // минимальная ставка в долларах для валюти от которой торгуем
            $min_USD_A_B = $order_A_B->min * $rate_CA_base->price;
            $min_USD_B_C = $order_B_C->min * $rate_BC_base->price;
            $min_USD_C_A = $order_C_A->min * $rate_CA_base->price;

            $this->min = min($min_USD_A_B, $min_USD_B_C, $min_USD_C_A); // находим самое меньшее

        } else {
            if(! $rate_AB_base) {
                $this->error = 'В таблице rate нет монети ' . $order_A_B->base_curr;
            } elseif (! $rate_BC_base) {
                $this->error = 'В таблице rate нет монети ' . $order_B_C->base_curr;
            } elseif ( ! $rate_CA_base ) {
                $this->error = 'В таблице rate нет монети ' . $order_C_A->base_curr;
            }
        }
    }

    private function simulateTrade($order_A_B, $order_B_C, $order_C_A, $A, $B, $C)
    {
        if(is_null($this->error)) {
            // тест торгов

            if($this->min > self::MY_MIN_LIMIT) {
                // Торгуем
                if($this->min > self::MY_MAX_LIMIT) {
                    $among = self::MY_MAX_LIMIT;
                } else {
                    $among = $this->min * 0.9;
                }

                // найти суму первой сделки
                $rate_A = Rate::where('symbol', $A)->first();
                $among_A = $among / $rate_A->price;

                // найти сумму второй сделки
                $among_B = $among_A * $order_A_B->price * $this->tax;

                // найти сумму третей сделки
                $among_C = $among_B * $order_B_C->price * $this->tax;

                // найти результат третей сделки
                $result = $among_C * $order_C_A->price * $this->tax;


                $comment = "$among_A ($A) -> $among_B ($B) | price: " .
                    (($order_A_B->price == $order_A_B->market_price) ? $order_A_B->market_price : '1 / ' . $order_A_B->market_price) . '<br>';

                $comment .= "$among_B ($B) -> $among_C ($C) | price: " .
                    (($order_B_C->price == $order_B_C->market_price) ? $order_B_C->market_price : '1 / ' . $order_B_C->market_price) . '<br>';

                $comment .= "$among_C ($C) -> $result ($A) | price: " .
                    (($order_C_A->price == $order_C_A->market_price) ? $order_C_A->market_price : '1 / ' . $order_C_A->market_price) . '<br>';

                $comment .= 'Прибиль: ' . ($result - $among_A) . ' ' . $A . ' = $' . ($result - $among_A) * $rate_A->price;

            } else {
                $comment = 'Минимальная ставка < $' . self::MY_MIN_LIMIT;
            }

            $this->comment = $comment;
        }
    }

}