<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    const ALLOWEDTYPES = array('date', 'number', 'string', 'boolean');
    
    protected $fillable = ['type', 'title', 'value'];
}
