<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{

    protected $guarded = [];
    public $timestamps = false;


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Exclude Coinmarketcap
         */
        static::addGlobalScope('wo_cmc', function(Builder $builder) {
            $builder->where('stocks.id', '<>', 50);
        });
    }


    public function getTradeUrlAttribute($value)
    {
        if(is_null($value)) {
            return $this->www;
        } else {
            return $value;
        }
    }


    /*----------------------------------------
     * Relationships
     *----------------------------------------
     */

    /**
     * Ключи для биржи
     */
    public function key()
    {
        return $this->hasOne(Key::class)->where('user_id', \Auth::id());
    }

    /**
     * Страна
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
