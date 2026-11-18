<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\PatchSelfUserRequest;
use App\Http\Requests\User\PatchUserRequest;
use App\Http\Requests\User\PostUserRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function post(PostUserRequest $request){
        $user=$request->user();
        $validated=$this->username_to_name($request->validated());
        if(!$this->check_admin_pass($user,$validated['pass_admin'])) return $this->wrong_admin_pass();
        if($validated['admin']&&$user->admin!=2){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 400
            ]);
        }
        if($user->admin!=2) $validated['admin']=0;
        if($user->admin==2 && $user->email!=getenv('ADMIN_ACCOUNT') && $validated['admin']>1)$validated['admin']=1;
        $validated['password']=password_hash(hash_hmac("sha256", $validated["password"], getenv('PEPPER')),PASSWORD_ARGON2ID);
        $employee=Employee::create($validated);
        return new SuccessResource([
            'message' => 'Added employee successfully'
        ]);
    }
    public function delete(DeleteUserRequest $request,Employee $employee){ 
        $validated=$request->validated();
        $user=$request->user();
        if(!$this->check_admin_pass($user,$validated['pass_admin'])) return $this->wrong_admin_pass();

        if(($employee->admin&&$user->admin!=2)||($employee->admin==2&&$user->email!=getenv('ADMIN_ACCOUNT'))){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 400
            ]);
        }
        if($employee==$user){
            return new ErrorResource([
                'message' => 'Nie można usunąć samego siebie',
                'status_code' => 422
            ]);
        }
        $employee->delete();
         return new SuccessResource([
            'message' => 'Deleted employee successfully'
        ]);
    }
    public function patch(PatchUserRequest $request, Employee $employee){
        $validated=$this->username_to_name($request->validated());
        $user=$request->user();
        if(!$this->check_admin_pass($user,$validated['pass_admin'])) return $this->wrong_admin_pass();
       
        if($user->admin!=2) $validated['admin']=0;
        if($user->admin==2 && $user->email!=getenv('ADMIN_ACCOUNT') && $validated['admin']>1)$validated['admin']=1;
        if($user==$employee) $validated['admin']=$user->admin;
        elseif(($employee->admin&&$user->admin!=2)||($employee->admin==2&&$user->email!=getenv('ADMIN_ACCOUNT'))){
            return new ErrorResource([
                'message' => 'Unauthorized',
                'status_code' => 400
            ]);
        }
        if(isset($validated['email'])&& $validated['email']!=$employee->email){
            $exists=Employee::where('email',$validated['email'])->where('employee_id','!=',$employee->email)->count();
            if($exists){
                return new ErrorResource([
                    'message' => 'Mail jest już zajęty',
                    'status_code' => 422
                ]);
            }
        }
        if(isset($validated['password'])&& $validated['password']) $validated['password']=password_hash(hash_hmac("sha256", $validated["password"], getenv('PEPPER')),PASSWORD_ARGON2ID);
        else unset($validated['password']);
        $employee->update($validated);
        DB::table('sessions')
            ->where('user_id',$employee->employee_id)
            ->delete();
        return new SuccessResource([
            'message' => 'Updated employee successfully'
        ]);
    }
    public function patch_self(PatchSelfUserRequest $request){
        $validated=$request->validated();
        $user=$request->user();
        if(!$this->check_admin_pass($user,$validated['pass_admin'])) return $this->wrong_admin_pass();
        if(isset($validated['email'])&& $validated['email']!=$user->email){
            $exists=Employee::where('email',$validated['email'])->where('employee_id','!=',$user->email)->count();
            if($exists){
                return new ErrorResource([
                    'message' => 'Mail jest już zajęty',
                    'status_code' => 422
                ]);
            }
        }
        if(isset($validated['password']) && $validated['password']) $validated['password']=password_hash(hash_hmac("sha256", $validated["password"], getenv('PEPPER')),PASSWORD_ARGON2ID);
        else unset($validated['password']);
        $user->update($validated);
        if(isset($validated['password'])){
            DB::table('sessions')
                ->where('user_id',$user->employee_id)
                ->delete();
        }
        return new SuccessResource([
            'message' => 'Updated employee successfully'
        ]);
    }
    public function get(Request $request, Employee $employee=null){
        $user=$request->user();
        if($employee&&$user->admin){
            $employees=Employee::select('name','email')->get();
            return response()->json($employees,200);
        }
        if($request->user()->admin) $employees=Employee::select('name','email','employee_id','admin')->get();
        else $employees=Employee::select('name','email','employee_id')->get();
        return response()->json($employees,200);

    }
    private function check_admin_pass(Employee $user, string $pass): bool{
        $pass=hash_hmac("sha256", $pass, getenv("PEPPER"));
        return password_verify($pass,$user->password);
    }
    // The frontend sends "username", but the pracownik column is "name".
    private function username_to_name(array $validated): array{
        if(array_key_exists('username',$validated)){
            $validated['name']=$validated['username'];
            unset($validated['username']);
        }
        return $validated;
    }
    private function wrong_admin_pass(){
        return new ErrorResource([
            'message' => 'Błędne hasło administratora',
            'status_code' => 401
        ]);
    }
}
