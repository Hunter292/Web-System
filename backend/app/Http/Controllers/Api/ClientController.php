<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\PatchClientRequest;
use App\Http\Requests\Auth\PostClientRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function get_client_rates(Request $request){
        if(!$request->user()->admin){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        //get month and year from url
        $month=NULL;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $month2=NULL;
        if(isset($_GET["month2"])&& $_GET["month2"]>=1&&$_GET["month2"]<13) $month2=$_GET["month2"];
        $year=NULL;
        if(isset($_GET["year"])) $year=$_GET["year"];
        //assemble sub query for sum of worked hours for each client
        $sub_sql="SELECT client_nip, sum(Minute(TIMEDIFF(time_finish,time_start))+Hour(TIMEDIFF(time_finish,time_start))*60)/60 as hours FROM praca";
        $get="";
        if($month&&$month2) $get.=" WHERE Month(date) BETWEEN :month AND :month2";
        elseif($month) $get.=" WHERE Month(date)=:month";
        if(!$get&&$year) $get.=" WHERE Year(date)=:year";
        elseif($year)$get.=" AND Year(date)=:year";

        $sub_sql.=$get;
        $sub_sql.=" GROUP BY client_nip";
        //assemble the main query
        $sql="SELECT client_nip,name, sum(amount)/hourss.hours as rate FROM klient JOIN faktura USING(client_nip) JOIN 
        ($sub_sql) as hourss using(client_nip)";
        $get="";
        if($month&&$month2) $get.=" WHERE Month(date) BETWEEN :month1 AND :month12";
        elseif($month) $get.=" WHERE Month(date)=:month1";
        if(!$get&&$year) $get.=" WHERE Year(date)=:year1";
        elseif($year)$get.=" AND Year(date)=:year";
        $sql.=$get;
        $sql.=" GROUP BY client_nip ORDER BY rate desc";
        $bindings=[];

        if($month){
            $bindings['month']=(int) $month;
            $bindings['month1']=(int) $month;
        }

        if($month && $month2){
            $bindings['month2']=(int) $month2;
            $bindings['month12']=(int) $month2;
        }

        if($year){
            $bindings['year']=(int) $year;
            $bindings['year1']=(int) $year;
        }
        $query=DB::select($sql, $bindings);
        return response()->json(['ok'=>true,$query],200);
    }
    public function get_client_work_done(Request $request, Client $client){
        $month=NULL;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $month2=NULL;
        if(isset($_GET["month2"])&& $_GET["month2"]>=1&&$_GET["month2"]<13) $month2=$_GET["month2"];
        $year=NULL;
        if(isset($_GET["year"])) $year=$_GET["year"];
        //prepare request
        $sql="SELECT work_type, pracownik.name as p_name,pracownik.email as p_email, sum(Minute(TIMEDIFF(time_finish,time_start))+Hour(TIMEDIFF(time_finish,time_start))*60)/60 as time
        FROM praca LEFT JOIN pracownik USING(employee_id) WHERE client_nip=:nip";
        $get="";
        if($month&&$month2) $get.=" AND Month(date) BETWEEN :month AND :month2";
        elseif($month) $get.=" AND Month(date)=:month";
        if($year) $get.=" AND Year(date)=:year";
        $sql.=$get;
        $sql.=" GROUP BY work_type,pracownik.email ORDER BY pracownik.name, work_type";

        $bindings=[];
        $bindings['nip']=$client->client_nip;

        if($month) $bindings['month']=(int)$month;
        if($month&&$month2) $bindings['month2']=(int)$month2;
        if($year) $bindings['year']=(int)$year;
        $query=DB::select($sql, $bindings);
        return response()->json(['ok'=>true,$query],200);
    }
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
