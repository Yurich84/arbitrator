<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = ['id', 'flag', 'value'];
    public $timestamps = false;
    protected $keyType = 'string';
}
