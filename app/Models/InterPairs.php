<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterPairs extends Model
{
    protected $table = 'inter_pairs';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * получаем key
     * @param $value
     * @return string
     */
    public function getVolumeAttribute($value)
    {
        return $value ?: 0;
    }

    /*----------------------------------------
     * Relationships
     *----------------------------------------
     */

    /**
     * Биржа
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);   //связь один ко многим
    }

}
