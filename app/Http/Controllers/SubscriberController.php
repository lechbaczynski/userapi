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
        
        $ret = Subscriber::create($request->all());
        return $ret;
    }

    public function update(Request $request, $id)
    {
        $article = Subscriber::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    // deleting turned off
    public function delete(Request $request, $id)
    {
        //$article = Subscriber::findOrFail($id);
        //$article->delete();

        return 403;
    }
}
