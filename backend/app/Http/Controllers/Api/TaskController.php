<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\PatchTaskRequest;
use App\Http\Requests\Task\PostTaskRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function get_new(Request $request){
        $user=$request->user();
        $tasks=$user->assignedTasks()->wherePivot('status',0)->count();
        return response()->json($tasks,200);
    }
    public function get_of_worker(Request $request){
        $user=$request->user();
        if(!isset($_GET["status"])||($_GET["status"]!=1&&$_GET["status"]!=0)) $status=1;
        else $status=$_GET["status"];
        $month=0;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $year=0;
        if(isset($_GET["year"])) $year=$_GET["year"];
        $sub_query=DB::table('task_worker')
                    ->join('zadanie', 'zadanie.task_id', '=', 'task_worker.task_id')
                    ->where('zadanie.employee_id', $user->employee_id)
                    ->where('task_worker.status', ($status?'<':'='), 3)
                    ->select('task_worker.task_id')
                    ->distinct();
        $tasks=$user->tasks()
            ->with('client:name,client_nip')
            ->whereIn('task_id',$sub_query)
            ->whereMonth('date',$month?'=':'>',$month)
            ->whereYear('date',$year?'=':'>',$year)
            ->orderBy('task_id',($status?'ASC':'DESC'))
            ->limit(100)
            ->get();
        // $tasks=Task::with("employee:name, employee_id")
        //     ->with('client:name,client_id')
        //     ->where('employee_id',$user->employee_id)
        //     ->wherePivot('status',($status?'<':'='),3)
        //     ->whereMonth('date',$month?'=':'>',$month)
        //     ->whereYear('date',$year?'=':'>',$year)
        //     ->orderBy('task_id',($status?'ASC':'DESC'))
        //     ->limit(100)
        //    ->get();
        return response()->json($tasks,200);
        
    }
    public function get_to_worker(Request $request){
        $user=$request->user();
        if(!isset($_GET["status"])||($_GET["status"]!=1&&$_GET["status"]!=0)) $status=1;
        else $status=$_GET["status"];
        $month=0;
        if(isset($_GET["month"])&&$_GET["month"]>=1&&$_GET["month"]<13) $month=$_GET["month"];
        $year=0;
        if(isset($_GET["year"])) $year=$_GET["year"];
        $tasks=$user->assignedTasks()
            ->with('client:name,client_nip')
            ->with("employee:name,employee_id")
            ->wherePivot('status',($status?'<':'='),3)
            ->whereMonth('date',$month?'=':'>',$month)
            ->whereYear('date',$year?'=':'>',$year)
            ->orderBy('task_id',($status?'ASC':'DESC'))
            ->limit(100)
            ->get();

        return response()->json($tasks,200);
        
    }
    public function post(PostTaskRequest $request){
        $validated=$request->validated();
        $user=$request->user();
        $client=Client::where('client_nip',$validated['client_nip'])->count();
        if(!$client){
            return new ErrorResource([
                'message' => 'Invalid nip',
                'status_code' => 422
            ]);
        }
        $users=Employee::whereIn('employee_id',$validated['victims'])->count();
        if(!$users|| $users<sizeof($validated['victims'])){
             return new ErrorResource([
                'message' => 'Invalid workers',
                'status_code' => 422
            ]);
        }

        DB::beginTransaction();
        try{
            $task=Task::create([
                "date"=>date('Y-m-d'),
                "deadline"=>$validated["deadline"],
                "employee_id"=>$user->employee_id,
                "client_nip"=>$validated["client_nip"],
                "description"=>$validated["description"],
                "priority"=>$validated["priority"],
            ]);
            foreach($validated['victims'] as $victim){
                $task->workers()->attach($victim,[
                    'status'=>0
                ]);
            }
            DB::commit();
            return new SuccessResource([
                'message' => 'Added task successfully'
            ]);

        }catch(Exception $e){
            DB::rollBack();
            return new ErrorResource([
                'message' => $e->getMessage(),
                'status_code' => 422
            ]);
        }
    }
    public function delete(Request $request, Task $task){
        if($task->employee!=$request->user()){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $task->delete();
        return new SuccessResource([
            'message' => 'Deleted task successfully'
        ]);
    }
    public function patch(PatchTaskRequest $request, Task $task){
        $validated=$request->validated();
        if($task->employee!=$request->user()){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $task->update($validated);
         return new SuccessResource([
            'message' => 'Updated task successfully'
        ]);
    }
    
}
