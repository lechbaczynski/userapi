<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use App\EmailValidator as EmailValidator;

class SubscriberController extends Controller
{
 
    public function index()
    {
        return response('Forbidden', 403);
        // return Subscriber::all();
    }
 
    public function show($id)
    {
        return response('Forbidden', 403);
        // return Subscriber::find($id);
    }

    public function store(Request $request)
    {
        
        $email = $request->input('email');
        
        // validate e-mail
        // use our class, encapsulating validation
        if (!$email || !EmailValidator::valid($email)) {
            $httpStatus = 422;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'Wrong e-mail',
                    'detail' => 'E-mail must be valid and domain must be active']],
            );
               
            return response()->json($returnData, $httpStatus);
        }

        // check if not reactivating existing user
        $checkSubscribers = Subscriber::where('email', $email)->get(); 
        // dd($checkSubscribers->count());
        if ($checkSubscribers->count()) {
            // already exists
            $httpStatus = 409;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'Duplicate e-mail',
                    'detail' => 'E-mail already exists']],
            );
               
            return response()->json($returnData, $httpStatus);
        }

        
        $subscriber = new Subscriber;
        $subscriber->email = $email;
        $subscriber->name = $request->input('name');
        
        
        // check for fields
        if ($request->input('fields') && is_array($request->input('fields'))) {
            // add fields
            
            $fields = $request->input('fields');
            foreach ($fields as $field) {
                
                
            }
        }
        
        // set account_id
        // set it to 1 now, maybe use it in next version
        $subscriber->account_id = 1;
        $subscriber->state = 'unconfirmed';
        $saved = $subscriber->save();
        
        if (!$saved) {
            $httpStatus = 500;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'  => 'Problem with adding subscriber',
                    'detail' => 'Subscriber cannot be saved']],
            );
               
            return response()->json($returnData, $httpStatus);  
        }
         
        $httpStatus = 201;
        $returnData = array(
            'created' => true,
            'status' => $httpStatus,
            'id' => $subscriber->id
        );
               
        return response()->json($returnData, $httpStatus);
        
        //$ret = Subscriber::create($request->all());
        //return $ret;
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
