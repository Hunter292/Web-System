<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\PostClientMailRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\Client;
use App\Models\ClientEmail;
use App\Models\Employee;
use App\Models\Work;
use Illuminate\Http\Request;


class ClientMailControllerController extends Controller
{
    public function post(PostClientMailRequest $request, Client $client)
    {
        $validated = $request->validated();
        $present=$client->emails->where('email',$validated['email'])->get();
        if($present){
            return new ErrorResource([
                'message' => 'Email already assigned to client',
                'status_code' => 422
            ]);
        }
        $mail=Clientemail::create($validated);
        $client->associate($mail);
        $client->save();
        return new SuccessResource([
            'message' => 'Added mail successfully'
        ]);
       
    }
    
    public function get(Request $request, Client $client){
        $emails=$client->emails->get();
        return response()->json(['ok'=>true,$emails],200);
    }



}
