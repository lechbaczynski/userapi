<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use App\Field;
use App\EmailValidator as EmailValidator;
use Illuminate\Validation\Rule;
use App\Http\Resources\SubscriberResource;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $rules = [
            'email' => 'required|max:255', // unique:subscribers - will check later
            'name'  => 'nullable|max:255',
        ];
         
        $response = $this->checkJSON($request, $rules);
        if ($response) {
            return $response;
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
                    (in_array($field['type'], Field::ALLOWEDTYPES) )
                    
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
        
        $rules = [
            'name' => 'nullable|max:255',
            'state' => [
                'nullable',
                Rule::in(Subscriber::ALLOWEDSTATES),
            ],
            // no e-mail - one should not be able to chane an e-mail of a subscriber
        ];
        $response = $this->checkJSON($request, $rules);
        if ($response) {
            return $response;
        }

        // check if not reactivating existing user
        $newState = $request->input('state');
        if (// is unsubscribed and someone wants to chane to any other state
                ( $subscriber->state == 'unsubscribed' && $newState != 'unsubscribed')
                // or is bounced and someone tries to make him active or unconfirmed
                ||
                ($subscriber->state == 'bounced' && ( $newState == 'active' || $newState == 'unconfirmed') )

            ) {
                $httpStatus = 422;
                $returnData = array(
                    'errors' => [
                    ['status' => $httpStatus,
                    'Title'  => 'Status change not allowed',
                    'detail' => 'Status change not allowed']],
                );
                return response()->json($returnData, $httpStatus);
        }

        if ($request->input('name')) {
            $subscriber->name = $request->input('name');
        }
        if ($request->input('state')) {
            $subscriber->state = $request->input('state');
        }
                
        $subscriber->save();
        
        $httpStatus = 200;
        $returnData = array(
            'updated' => true,
            'status' => $httpStatus,
            'id' => $subscriber->id
        );
               
        return response()->json($returnData, $httpStatus);
        
        //$subscriber->update($request->all());
        // return $subscriber;
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
