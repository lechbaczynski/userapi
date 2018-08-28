<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Field;
use App\Http\Resources\FieldResource;

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
            'title' => 'required|max:255', // unique:subscribers - will check later
            'type'  => 'required|max:255',
        ];
        
        $data = $this->checkJSON($request, $rules);
        

        // validate
        
        
        // check if subscriber exists
        
        // check if field exist?
        
        // add field
        
        
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
        //
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
