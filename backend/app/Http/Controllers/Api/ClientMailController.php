<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\ClientMail\PostClientMailRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Client;
use App\Models\ClientEmail;
use Illuminate\Http\Request;


class ClientMailController extends Controller
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
        $validated['client_nip']=$client->client_nip;
        $mail=Clientemail::create($validated);
        return new SuccessResource([
            'message' => 'Added mail successfully'
        ]);
       
    }
    
    public function get(Request $request, Client $client){
        $emails=$client->emails->get();
        return response()->json($emails,200);
    }



}