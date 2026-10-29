<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Income\PatchIncomeRequest;
use App\Http\Requests\Income\PostIncomeBatchRequest;
use App\Http\Requests\Income\PostIncomeSingleRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Client;
use App\Models\Invoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function post_batch(PostIncomeBatchRequest $request)
    {
        $validated = $request->validated();
        $input=$validated['data'];
        if(!array_key_exists('NIP', $input[0])||!array_key_exists('Kontrahent', $input[0])||
        !array_key_exists('Netto', $input[0])||!array_key_exists('Data wyst.', $input[0])){
            return new ErrorResource([
                'message' => 'missing data',
                'status_code' => 422
            ]);
        }
        //find clients not in the database and add them
        $clients=Client::get();
        $insert_nips=[];
        $new_nip_flag=true;
        foreach($input as $row){
            $new_nip_flag=true;
            foreach($clients as $nip){
                if($row["NIP"]==$nip["client_nip"]){
                    $new_nip_flag=false;
                    break;
                }
            }
            if($new_nip_flag){
                array_push($insert_nips,['client_nip'=>$row['NIP'],'name'=>$row["Kontrahent"]]);
                array_push($clients,["client_nip"=>$row["NIP"]]);
            }
        }
        
        //mode==1 means insert only clients
        if(isset($_GET["mode"])&& $_GET["mode"]==1){
            if($insert_nips) Client::insert($insert_nips);
            return new SuccessResource([
            '   message' => 'Added clients successfully'
            ]);
        }
        $inserts=[];
        foreach($input as $in){
            array_push($inserts,['client_nip'=>$in['NIP'],'amount'=>$in['Netto'],'date'=>$in['Data wyst.']]);
        }
        DB::beginTransaction();
        try{
            if($insert_nips) Client::insert($insert_nips);
            Invoice::insert($inserts);
            DB::commit();
            return new SuccessResource([
            '   message' => 'Added invoices successfully'
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return new ErrorResource([
                'message' => 'Server error',
                'status_code' => 422
            ]);
        }

    }
    public function post_single(PostIncomeSingleRequest $request)
    {
        $validated = $request->validated();
        $client=Client::where('client_nip',$validated['client_nip']);
        if(!$client){
            return new ErrorResource([
                'message' => 'Invalid data',
                'status_code' => 422
            ]);
        }
        $validated['client_nip']=$client->client_nip;
        $invoice=Invoice::create($validated);
        return new SuccessResource([
                'message' => 'Added invoice successfully'
            ]);
    }
    
    public function get(Request $request){
        if(!$request->user()->admin){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
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
        return response()->json($invoices,200);
        
        
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
