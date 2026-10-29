<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function get_staff_work_done(Request $request, Employee $employee){
        if(!$request->user()->admin){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $month=NULL;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $month2=NULL;
        if(isset($_GET["month2"])&& $_GET["month2"]>=1&&$_GET["month2"]<13) $month2=$_GET["month2"];
        $year=NULL;
        if(isset($_GET["year"])) $year=$_GET["year"];
        $date=NULL;
        if(isset($_GET["date"])) $date=$_GET["date"];
        $client_id=NULL;
        if(isset($_GET["id"])) $client_id=$_GET["id"];
        //prepare request
        $sql="SELECT work_type, name,date,time_start, (Minute(TIMEDIFF(time_finish,time_start))+Hour(TIMEDIFF(time_finish,time_start))*60)/60 as time
        FROM praca LEFT JOIN klient USING(client_nip) WHERE employee_id=:id";
        if($month&&$month2) $sql.=" AND Month(date) BETWEEN :month AND :month2";
        elseif($month) $sql.=" AND Month(date)=:month";
        if($year)$sql.=" AND Year(date)=:year";
        if($date) $sql.=" AND date=:date";
        if($client_id)$sql.=" AND client_nip=:nip";
        $sql.=" ORDER BY name,work_type LIMIT 200";

        $bindings=[];
        $bindings['id']=$employee->employee_id;
        if($month)$bindings['month']=(int)$month;
        if($month&&$month2)$bindings['month2']=(int)$month2;
        if($year)$bindings['year']=(int)$year;
        if($date)$bindings['date']=(string)$date;
        if($client_id)$bindings['client_id']=(string)$client_id;

        $query=DB::select($sql,$bindings);
        return response()->json($query,200);

    }
    public function get_staff_stats(Request $request){
        if(!$request->user()->admin){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $month=NULL;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $month2=NULL;
        if(isset($_GET["month2"])&& $_GET["month2"]>=1&&$_GET["month2"]<13) $month2=$_GET["month2"];
        $year=NULL;
        if(isset($_GET["year"])) $year=$_GET["year"];
        //prepare request
        $sql="SELECT work_type,name,email,employee_id, sum(Minute(TIMEDIFF(time_finish,time_start))+Hour(TIMEDIFF(time_finish,time_start))*60)/60 as time
        FROM praca JOIN pracownik USING(employee_id)";
        $get="";
        if($month&&$month2) $get.=" WHERE Month(date) BETWEEN :month AND :month2";
        elseif($month) $get.=" WHERE Month(date)=:month";
        if(!$get&&$year) $get.=" WHERE Year(date)=:year";
        elseif($year)$get.=" AND Year(date)=:year";
        $sql.=$get;
        $sql.=" GROUP BY work_type,email,name,employee_id ORDER BY email,work_type";

        $bindings=[];
        if($month)$bindings['month']=(int)$month;
        if($month&&$month2)$bindings['month2']=(int)$month2;
        if($year)$bindings['year']=(int)$year;

        $query=DB::select($sql,$bindings);
        return response()->json($query,200);
    }
}
