<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{

    protected $guarded = [];
    public $timestamps = false;


    /*----------------------------------------
     * Mutators
     *----------------------------------------
     */
    /**
     * получаем key
     * @param $value
     * @return string
     */
    public function getKeyAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * сохраняем key
     * @param $value
     */
    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = encrypt($value);
    }

    /**
     * получаем secret
     * @param $value
     * @return string
     */
    public function getSecretAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * сохраняем secret
     * @param $value
     */
    public function setSecretAttribute($value)
    {
        $this->attributes['secret'] = encrypt($value);
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

    /**
     * User
     */
    public function user()
    {
        return $this->belongsTo(User::class);   //связь один ко многим
    }
}
