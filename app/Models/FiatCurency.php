<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiatCurency extends Model
{
    protected $table = 'fiat_currencies';
    protected $guarded = ['id', 'value'];
    public $timestamps = false;
    protected $keyType = 'string';
}
