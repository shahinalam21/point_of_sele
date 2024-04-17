<?php
    namespace App\Http\Controllers;
    use App\Helper\JWTToken;
    use App\Mail\OTPMail;
    use App\Models\User;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Mail;

    class UserController extends Controller
    {
    public function UserRegistration(Request $request){
        try{
            User::create([
                'firstName'=>$request->input('firstName'),
                'lastName'=>$request->input('lastName'),
                'email'=>$request->input('email'),
                'mobile'=>$request->input('mobile'),
                'password'=>$request->input('password'),
                'role'=>$request->input('role'),
            ]);
            return response()->json([
                'status' =>'success',
                'message' => 'User created successfully'
            ], 201);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'faild',
                'message' => "user registration failed"
            ], 400);
        }
    }
    public function UserLogin(Request $request){
            $count = User::where('email','=', $request->input('email'))
            ->where('password','=', $request->input('password'))
            ->count();

            if($count == 1){
                $token = JWTToken::CreateToken($request->input('email'));
                return response()->json([
                'status' =>'success',
                'message' => "user logged in successfully",
                'token' => $token
                
                ], 200);
            }
            else{
                return response()->json([
                'status' => 'faild',
                'message' => "user login failed"
                ], 400);
            }
    }
    public function SendOTPcode(Request $request){
            $email = $request->input('email');
            $otp = rand(1000,9999);
            $count = User::where('email','=', $email)->count();

            if($count == 1){
                // otp email address
            Mail::to($email)->send(new OTPMail($otp));
                //otp code table update
            User::where('email','=', $email)->update(['otp'=>$otp]);
            return response()->json([
                'status' =>'success',
                'message' => "OTP send successfully"
                ], 200);
            }
            else{
                return response()->json([
                'status' => 'faild',
                'message' => "OTP send failed"
                ], 400);
            }
    }
    }
