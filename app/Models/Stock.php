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


    /*----------------------------------------
     * Relationships
     *----------------------------------------
     */

    /**
     * Биржа
     */
    public function key()
    {
        return $this->hasOne(Key::class)->where('user_id', \Auth::id());
    }


}
