<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriangleFork extends Model
{
    protected $table = 'triangle_forks';
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
