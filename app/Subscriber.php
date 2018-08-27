<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['account_id', 'name', 'email', 'state'];
    
    public function fields()
    {
        return $this->hasMany('App\Field');
    }
}
