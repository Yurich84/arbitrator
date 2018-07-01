<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{

    protected $guarded = [];
    public $timestamps = false;



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
