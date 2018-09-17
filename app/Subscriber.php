<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    const ALLOWEDSTATES = array( 'active',
                                    'unsubscribed',
                                    'junk',
                                    'bounced',
                                    'unconfirmed');

    protected $fillable = ['account_id', 'name', 'email', 'state'];
    
    public function fields()
    {
        return $this->hasMany('App\Field');
    }
}
