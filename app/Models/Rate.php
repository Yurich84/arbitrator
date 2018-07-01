<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $guarded = [];
    public $timestamps = false;


    /**
     * Находим цену
     * @param $value
     * @return mixed
     */
    public function getPriceAttribute($value)
    {
        $now = Carbon::now();
        if(is_null($value) || Carbon::parse($this->updated_at)->diffInHours($now) > 24 ) {
            $ticker = json_decode(file_get_contents("https://api.coinmarketcap.com/v2/ticker/{$this->id}/"));

            if ($ticker->data) {
                $value = $this->price = $ticker->data->quotes->USD->price;
                $this->updated_at = Carbon::createFromTimestamp($ticker->data->last_updated);
                $this->save();

            } else {
                dd($ticker->metadata->error);
            }
        }
        return $value;
    }

}
