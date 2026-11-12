<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\UserResource;
use App\Models\Employee;
use App\Notifications\SendOtpNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        // $user = Employee::where('email', $validated['email'])->first();

        // if (!$user || !password_verify(hash_hmac("sha256", $validated["password"], getenv("PEPPER")),$user->password)) {
        //     return new ErrorResource([
        //         'message' => 'Invalid credentials',
        //         'status_code' => 401
        //     ]);
        // }
        
        // $token = $user->createToken('auth_token')->plainTextToken;
       
        // $authData = [
        //     'user' => $user,
        //     'token' => $token,
        //     'token_type' => 'Bearer'
        // ];

        // return new AuthResource($authData);
        $validated['password']=hash_hmac("sha256", $validated["password"], getenv("PEPPER"));
        if (!Auth::attempt($validated)) {
            return new ErrorResource([
                'message' => 'Invalid credentials',
                'status_code' => 401
            ]);
        }
        $token=rand(100000, 999999);
        $user=Employee::where('email',$validated['email'])->first();
        $user->update([
            "otp_token"=>$token,
            "token_life"=>now()->addMinutes(10)
        ]);
        $user->notify(new SendOtpNotification($token));
        return new SuccessResource([
            'message' => 'Proced to second step'
        ]);
        // $request->session()->regenerate();
        // $user = Auth::user();
        // return response()->json([
        //    'id' => $user->employee_id,
        //     'email' => $user->email,
        //     'name' => $user->name,
        //     'admin'=>$user->admin,
        //     'time' =>date("Y-m-d H:i:s"),
        //     'message' => 'User authenticated successfully',
        // ]);
    }
    public function auth2F(Request $request){
        $validated = $request->validated();

        $validated['password']=hash_hmac("sha256", $validated["password"], getenv("PEPPER"));
        if (!Auth::attempt($validated)) {
            return new ErrorResource([
                'message' => 'Invalid credentials',
                'status_code' => 401
            ]);
        }
        $user=Employee::where('email',$validated['email'])->first();
        if($user->token!=$validated['token'] || $user->token_life->isPast()){
             return new ErrorResource([
                'message' => 'Invalid otp',
                'status_code' => 401
            ]);
        }
        $request->session()->regenerate();
        $user = Auth::user();
        return response()->json([
           'id' => $user->employee_id,
            'email' => $user->email,
            'name' => $user->name,
            'admin'=>$user->admin,
            'time' =>date("Y-m-d H:i:s"),
            'message' => 'User authenticated successfully',
        ]);

    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return new SuccessResource([
            'message' => 'Logged out successfully'
        ]);
    }
    public function forgot_password(Request $request){
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return new SuccessResource([
            'message' => 'Email został wysłany'
        ]);
        // return $status === Password::ResetLinkSent
        //     ? back()->with(['status' => __($status)])
        //     : back()->withErrors(['email' => __($status)]);
    }
    public function reset_password(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (Employee $user, string $password) {
            $user->forceFill([
                'password' => password_hash(hash_hmac("sha256", $password, getenv("PEPPER")),PASSWORD_ARGON2ID)
            ]);
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
     return new SuccessResource([
            'message' => 'Hasło zresetowane'
        ]);
    // return $status === Password::PasswordReset
    //     ? redirect()->route('login')->with('status', __($status))
    //     : back()->withErrors(['email' => [__($status)]]);
    }

//The user() function retrieves the authenticated user's profile data using their Bearer token.

    public function user(Request $request)
    {
       
    }
}
