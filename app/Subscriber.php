<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['accounts_id', 'name', 'email', 'state']; 
    
    
}
