<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use App\Field;
use App\EmailValidator as EmailValidator;
use App\Http\Resources\SubscriberResource;

use Validator;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: 403
        // It should be forbidden, but I am leaving it now, so ypu can easily 
        // query the API
        // return response('Forbidden', 403);
        
        return SubscriberResource::collection(Subscriber::all());
        
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =  $request->json()->all();

        if (!$data) {
            // no payload or wrong payload
            $httpStatus = 422;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'Wrong data sent',
                    'detail' => 'The JOSN sent is empty or incorrect']],
            );
            return response()->json($returnData, $httpStatus);
        }
        
        
        
         $rules = [
            'email' => 'required|max:255', // unique:subscribers - will check later
            'name'  => 'nullable|max:255',
         ];
        
        $validator = Validator::make($data, $rules);
        if (!$validator->passes()) {
            // dd($validator->errors()->all());
            
            $httpStatus = 422;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'Incorrect data sent',
                    'detail' => 'The JOSN sent is incorrect']],
            );
            return response()->json($returnData, $httpStatus);
        }
    

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
        
        
        
        
           $fields = $request->input('fields');
        
        // check for fields
        if ($fields && is_array($fields)) {
            // check fields
            foreach ($fields as $field) {
                // check if field has a type
                // and name
                if (!(
                    ($field['title'])
                    &&
                    (in_array($field['type'], Field::$allowedTypes) )
                    
                    ) ) {
                        $httpStatus = 422;
                        $returnData = array(
                        'errors' => [
                            ['status' => $httpStatus,
                            'Title'  => 'Wrong fields',
                            'detail' => 'Error in fields']],
                        );
               
                        return response()->json($returnData, $httpStatus);
                }
            }
        }
        
        
        $subscriber = new Subscriber;
        $subscriber->email = $email;
        $subscriber->name = $request->input('name');
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

        // add fields
        if ($fields && is_array($fields)) {
            foreach ($fields as $field) {
                $field = $subscriber->fields()->create($field);
            }
        }
       
        $httpStatus = 201;
        $returnData = array(
            'created' => true,
            'status' => $httpStatus,
            'id' => $subscriber->id
        );
               
        return response()->json($returnData, $httpStatus);
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $subscriber = Subscriber::findOrFail($id);
        return new SubscriberResource($subscriber);
             
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $subscriber = Subscriber::findOrFail($id);
        
        // check if not reactivating existing user
        // TODO
        
        
        $subscriber->update($request->all());

        return $subscriber;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response('Forbidden', 403);
    }
}
