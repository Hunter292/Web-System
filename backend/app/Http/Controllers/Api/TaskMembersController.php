<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskMembersController extends Controller
{
    public function post(Request $request, Task $task, Employee $employee){
        if($task->employee!=$request->user()){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $task->workers()->attach($employee->employee_id,[
            'status'=>0
        ]);
         return new SuccessResource([
            'message' => 'Added user to task successfully'
        ]);
    }
    public function get_of_worker(Request $request){
        if(!isset($_GET["status"])||($_GET["status"]!=1&&$_GET["status"]!=0)) $status=1;
        else $status=$_GET["status"];
        $month=0;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $year=0;
        if(isset($_GET["year"])) $year=$_GET["year"];
        $sub_query=DB::table('task_worker')
                ->join('zadanie','zadanie.task_id','=','task_worker.task_id')
                ->where('status',($status?'<':'='),3)
                ->where('zadanie.employee_id',$request->user()->employee_id)
                ->select('task_worker.task_id');
        $query=DB::table('task_worker')
                ->join('pracownik','pracownik.employee_id','=','task_worker.employee_id')
                ->join('zadanie','zadanie.task_id','=','task_worker.task_id')
                ->whereIn('task_worker.task_id',$sub_query)
                ->whereMonth('date',$month?'=':'>',$month)
                ->whereYear('date',$year?'=':'>',$year)
                ->orderBy('task_id',($status?'ASC':'DESC'))
                ->limit(100)
                ->select(
                    'task_worker.task_id',
                    'task_worker.status',
                    'pracownik.name',
                    'pracownik.email',
                    'task_worker.employee_id'
                );
        $results = $query->get();
        return response()->json($results,200);

    }
    public function get_to_worker(Request $request){
        if(!isset($_GET["status"])||($_GET["status"]!=1&&$_GET["status"]!=0)) $status=1;
        else $status=$_GET["status"];
        $month=0;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $year=0;
        if(isset($_GET["year"])) $year=$_GET["year"];
         $sub_query=DB::table('task_worker')
                ->where('status',($status?'<':'='),3)
                ->where('employee_id',$request->user()->employee_id)
                ->select('task_id');
        $query=DB::table('task_worker')
                ->join('zadanie','zadanie.task_id','=','task_worker.task_id')
                ->join('pracownik','pracownik.employee_id','=','task_worker.employee_id')
                ->whereIn('zadanie.task_id',$sub_query)
                ->whereMonth('date',$month?'=':'>',$month)
                ->whereYear('date',$year?'=':'>',$year)
                ->orderBy('task_id',($status?'ASC':'DESC'))
                ->limit(300)
                ->select(
                    'task_worker.task_id',
                    'task_worker.status',
                    'pracownik.name',
                    'pracownik.email',
                    'task_worker.employee_id'
                );
        $results = $query->get();
        return response()->json($results,200);
    }
    public function patch(Request $request,Task $task){
        if(!$request->user()->assignedTasks()->where('zadanie.task_id',$task->task_id)->count()){
            return new ErrorResource([
                'message' => 'Assigment missing or employee not part of it',
                'status_code' => 422
            ]);
        }
        if(!$request->input('status')){
            return new ErrorResource([
                'message' => 'Missing data',
                'status_code' => 422
            ]);
        }
        $status=$request->input('status');
        if($status>3)$status=3;
        if($status<0)$status=0;
        $task->workers()->updateExistingPivot($request->user()->employee_id,['status'=>$status]);
        return new SuccessResource([
            'message' => 'Updated task status successfully'
        ]);

    }
    public function delete(Request $request, Task $task, Employee $employee){
        if($task->employee!=$request->user()){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $task->workers()->detach($employee->employee_id);
        return new SuccessResource([
            'message' => 'Detached employee from successfully'
        ]);
    }
}
