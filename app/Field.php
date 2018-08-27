<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['type', 'title', 'value'];
    public static $allowedTypes = ['date', 'number', 'string', 'boolean'];
}
