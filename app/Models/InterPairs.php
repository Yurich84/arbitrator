<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterPairs extends Model
{
    protected $table = 'inter_pairs';
    protected $guarded = [];
    public $timestamps = false;

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
