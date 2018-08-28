<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Field;
use App\Subscriber;
use App\Http\Resources\FieldResource;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FieldResource::collection(Field::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check if JSON correct
        
        $rules = [
            'title' => 'required|max:255',
            'type' => [
                'required',
                'max:255',
                Rule::in(Field::$allowedTypes),
            ],
            'value' => 'nullable|max:255',
            'subscriber_email'  => 'required|max:255',
        ];
        
        $response = $this->checkJSON($request, $rules);
        if ($response) {
            return $response;
        }
           
        // check if subscriber exists
        $subscriberEmail = $request->input('subscriber_email');
        $subscriber = null;
        $subscriber = Subscriber::where('email', $subscriberEmail)->first();
        if (!$subscriber) {
            $httpStatus = 422;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'E-mail does not exist',
                    'detail' => 'E-mail does not exist']],
            );
            return response()->json($returnData, $httpStatus);
        }
        
        // check if field exist?
        $fieldTitle = $request->input('title');

        $fields = $subscriber->fields;
        
        foreach ($fields as $field) {
            if ($field->title == $fieldTitle) {
                $httpStatus = 409;
                $returnData = array(
                    'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'Field already exists',
                    'detail' => 'Field already exists']],
                );
                return response()->json($returnData, $httpStatus);
            }
        }
        
        
        // add field
        $field = new Field([
            'title' => $fieldTitle,
            'type' => $request->input('type'),
            'value' => $request->input('value'),
        ]);
        
        $subscriber->fields()->save($field);
        
        $httpStatus = 201;
        $returnData = array(
            'created' => true,
            'status' => $httpStatus,
            'id' => $field->id
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
        $field = Field::findOrFail($id);
        return new FieldResource($field);
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
          $rules = [
            'title' => 'required|max:255',
            'type' => [
                'required',
                'max:255',
                Rule::in(Field::$allowedTypes),
            ],
            'value' => 'nullable|max:255',
        ];
        
        $response = $this->checkJSON($request, $rules);
        if ($response) {
            return $response;
        }

        try {
            $field = Field::FindOrFail($id);
        } catch(ModelNotFoundException $e) {
            $httpStatus = 404;
            $returnData = array(
                'errors' => [
                ['status' => $httpStatus,
                'Title'     => 'Field not found',
               'detail' => 'Field not found']],
            );
            return response()->json($returnData, $httpStatus);
        }

        
        $field->title = $request->input('title');
        $field->type = $request->input('type');
        if ($request->input('value') !== null ) {
            $field->value = $request->input('value');
        }
        
        $field->save();
        
        $httpStatus = 200;
        $returnData = array(
            'created' => true,
            'status' => $httpStatus,
            'id' => $field->id
        );
               
        return response()->json($returnData, $httpStatus);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
