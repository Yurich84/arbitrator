<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
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
    public function stock()
    {
        return $this->belongsTo(Stock::class);   //связь один ко многим
    }

    /**
     * User
     */
    public function user()
    {
        return $this->belongsTo(User::class);   //связь один ко многим
    }
}
