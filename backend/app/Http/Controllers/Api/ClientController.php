<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\Employee;
use App\Models\Work;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function post(PostClientRequest $request)
    {
        $validated = $request->validated();
        $client=Client::create($validated);
        
        
        return new SuccessResource([
            'message' => 'Added client successfully'
        ]);
       
    }
    public function delete(Request $request,Client $client){
        if($request->user()->admin==0){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $client->delete();
        return new SuccessResource([
            'message' => 'Deleted client successfully'
        ]);
    }
    public function patch(PatchClientRequest $request,Client $client){
        $validated = $request->validated();
        if($request->user()->role==0){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $client->update($validated);
        return new SuccessResource([
            'message' => 'Updated client successfully'
        ]);
    }
    public function get(Request $request){
        $clients=Client::orderBy('name','ASC')->get();
        return response()->json(['ok'=>true,$clients],200);
    }



}
