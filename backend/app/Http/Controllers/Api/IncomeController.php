<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\ClientEmail;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Work;
use Illuminate\Http\Request;


class IncomeController extends Controller
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
    
    public function get(Request $request){
       $month=NULL;
        if(isset($_GET["month"])&& $_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $month2=NULL;
        if(isset($_GET["month2"])&& $_GET["month2"]>=1&&$_GET["month2"]<13) $month2=$_GET["month2"];
        if(!$month2)$month2=$month;
        if(!$month){
            $month=1;
            $month2=12;
        }
        $year=NULL;
        if(isset($_GET["year"])) $year=$_GET["year"];
        if(!$year) $year=date('Y');
        $client=NULL;
        $invoices=null;
        if(isset($_GET["client"])){
            $client=$_GET["client"];
            $invoices=Invoice::where('client_nip',$client)->orderBy('name','ASC')->limit(100)->get();
        }else{
            $invoices=Invoice::whereYear('date',$year)->whereMonth('date','>=',$month)->whereMonth('date','<=',$month2)
            ->orderBy('name','ASC')->limit(100)->get();
        }
        return response()->json(['ok'=>true,$invoices],200);
        
        
    }
    public function patch(PatchIncomeRequest $request, Invoice $invoice){
        $validated=$request->validated();
        $invoice->update($validated);
        return new SuccessResource([
            'message' => 'Updated invoice successfully'
        ]);
    }
    public function delete(Request $request, Invoice $invoice){
        if($request->user()->admin==0){
             return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $invoice->delete();
        return new SuccessResource([
            'message' => 'Updated invoice successfully'
        ]);
    }

}
