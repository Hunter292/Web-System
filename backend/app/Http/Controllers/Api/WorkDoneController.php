<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Workdone\PatchWorkdoneRequest;
use App\Http\Requests\Workdone\PostWorkdoneRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Client;
use App\Models\Work;
use Illuminate\Http\Request;


class WorkDoneController extends Controller
{
    public function post(PostWorkdoneRequest $request)
    {
        $validated = $request->validated();
        $client=Client::where('client_nip',$validated['client_nip'])->first();
        if(!$client){
            return new ErrorResource([
                'message' => 'Invalid data',
                'status_code' => 422
            ]);
        }
        $validated['employee_id']=$request->user()->employee_id;
        $work=Work::create($validated);
        return new SuccessResource([
            'message' => 'Added work successfully'
        ]);
       
    }
    public function delete(Request $request,Work $work){
        if($request->user()!=$work->employee()->first()){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $work->delete();
        return new SuccessResource([
            'message' => 'Deleted work successfully'
        ]);
    }
    public function patch(PatchWorkdoneRequest $request,Work $work){
        $validated = $request->validated();
        if($request->user()!=$work->employee()->first()){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 401
            ]);
        }
        $work->update($validated);
        return new SuccessResource([
            'message' => 'Updated work successfully'
        ]);
    }
    public function get(Request $request){
        $user=$request->user();
        $work=$user->works()->with('client:name,client_nip')->orderBy('date','DESC')->orderBy('work_id','DESC')->limit(100)->get();
        if(isset($_GET['id']))$work=$work->where('client_nip',$_GET['id']);
        if(isset($_GET['date']))$work=$work->where('date',$_GET['date']);
        return response()->json($work,200);
    }



}
