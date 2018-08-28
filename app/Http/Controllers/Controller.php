<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function checkJSON(\Illuminate\Http\Request $request, array $rules) {
        $data =  $request->json()->all();

        if (!$data) {
            // no payload or wrong payload
            $httpStatus = 422;
            $returnData = array(
                'errors' => [
                    ['status' => $httpStatus,
                    'Title'     => 'Wrong data sent',
                    'detail' => 'The JSON sent is empty or incorrect']],
            );
            return response()->json($returnData, $httpStatus);
        }
        
               
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

        
        return $data;
    }
    
}
