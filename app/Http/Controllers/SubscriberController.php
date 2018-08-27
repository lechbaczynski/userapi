<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use App\EmailValidator as EmailValidator;

class SubscriberController extends Controller
{
 
    public function index()
    {
        return 403;
        // return Subscriber::all();
    }
 
    public function show($id)
    {
        return 403;  
        // return Subscriber::find($id);
    }

    public function store(Request $request)
    {
        
        $email = $request->input('email');
        
        // validate e-mail
        // use our class, encapsulating validation
        if (!EmailValidator::valid($email))  {
              
            $httpStatus = 422;
            $returnData = array(
                'errors' => [['status' => $httpStatus,
                'Title' => 'Wrong e-mail',
                'detail' => "E-mail must be valid and domain must be active"]],
            );
               
            return response()->json($returnData, $httpStatus);
               
         }
        
        // check if not reactivating existing user
         
        $subscriber = new Subscriber;
        $subscriber->email = $email;
        $subscriber->name = $request->input('name');
         
        // set account_id 
        // set it to 1 now, maybe use it in next version
        $subscriber->account_id = 1;
        $subscriber->state = 'unconfirmed';
         
        $ret = Subscriber::create($request->all());
        return $ret;
        // 201
        
    }

    public function update(Request $request, $id)
    {
        $subscriber = Subscriber::findOrFail($id);
        
        // check if not reactivating existing user
        
        $subscriber->update($request->all());

        return $subscriber;
    }

    // deleting turned off
    public function delete(Request $request, $id)
    {
        //$subscriber = Subscriber::findOrFail($id);
        //$subscriber->delete();

        return 403;
    }
}
