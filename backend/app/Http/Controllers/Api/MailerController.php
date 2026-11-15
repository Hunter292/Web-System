<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\PostTaxReportRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Mail\TaxReport;
use App\Models\Client;
use App\Models\ClientEmail;
use App\Models\Report;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\throwException;

class MailerController extends Controller
{
    public function post(PostTaxReportRequest $request){
        $validated=$request->validated();
        $client=Client::where('client_nip',$validated['client_nip'])->first();
        if(!$client){
            return new ErrorResource([
                'message' => 'Invalid client',
                'status_code' => 422
            ]);
        }
        $emails=$validated["emails"];
        $content="";
        for($i=0;$i<sizeof($validated["taxes"]);$i++){
            $content.=$validated["taxes"][$i]." - ".$validated["taxes_v"][$i]." zł termin płatności:".$validated["taxes_m"][$i];
            $content.=";";
        }
        $date=$validated['date'];
        $validated['date']=date('Y-m-d');
        $validated['employee_id']=$request->user()->employee_id;
        $validated['content']=$content;
        DB::beginTransaction();
        try{
            $report=Report::create($validated);
            foreach($emails as $email){
                $report->emails()->attach($email);
            }
            $email_values=ClientEmail::whereIn('email_id',$emails)->get();

            foreach ($email_values as $recipient) {
                Mail::to($recipient['email'])->send(new TaxReport($request->user(),$validated["taxes"],$validated["taxes_v"],$validated["taxes_m"],
                                                         $date,$validated['notes']));
            }
            DB::commit();
            return new SuccessResource([
                'message' => 'Sent emails successfully'
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return new ErrorResource([
                'message' => $e->getMessage(),
                'status_code' => 500
            ]);
        }
    }
    public function get_report(Request $request){
        $month=0;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $year=0;
        if(isset($_GET["year"])) $year=$_GET["year"];
        $client='';
        if(isset($_GET["client"])) $client=$_GET["client"];
        $reports=Report::with('employee:name,employee_id')->with('client:name,client_nip')
                        ->whereMonth('date',($month?'=':'!='),$month)
                        ->whereYear('date',($year?'=':'!='),$year)
                        ->where('client_nip',($client?'=':'!='),$client)
                        ->orderBy('report_id', 'DESC')
                        ->limit(200)
                        ->get();
        return response()->json($reports,200);
        
    }
    public function get_report_emails(Request $request){
        $month=0;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $year=0;
        if(isset($_GET["year"])) $year=$_GET["year"];
        $client='';
        if(isset($_GET["client"])) $client=$_GET["client"];

        $query=DB::table('email_report')
                 ->join('email_client','email_client.email_id','=','email_report.email_id')
                 ->join('raport','raport.report_id','=','email_report.report_id')
                 ->whereMonth('raport.date',($month?'=':'!='),$month)
                 ->whereYear('raport.date',($year?'=':'!='),$year)
                 ->where('raport.client_nip',($client?'=':'!='),$client)
                 ->orderBy('report_id', 'DESC')
                 ->limit(200)
                 ->select(
                    'email_report.report_id',
                    'email'
                );
        $reports=$query->get();
        return response()->json($reports,200);
    }
}
