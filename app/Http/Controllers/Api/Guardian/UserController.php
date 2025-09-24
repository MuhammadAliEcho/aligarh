<?php

namespace App\Http\Controllers\Api\Guardian;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Guardian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public $successStatus = 200;
    protected $PostLoginData, $LoginUserIDKey;


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->LoginUserIDKey = 'name';
        $request->merge([
            $this->LoginUserIDKey => $request->input('user_id'),
            'user_type' => 'guardian'
        ]);

        $this->PostLoginData = [
            $this->LoginUserIDKey => $request->input($this->LoginUserIDKey),
            'password' => $request->input('password'),
            'user_type' => 'guardian'
        ];

        $response = $this->ValidateLogin($request);
        if ($response !== true) {
            return $response;
        }

        if (Auth::validate($this->PostLoginData)) {
            $user = Auth::getLastAttempted();

            if (!$user->active) {
                return response()->json([
                    'error' => 'unauthorized',
                    'msg' => 'You must be Active to login'
                ], 401);
            }
        }

        if (Auth::attempt($this->PostLoginData)) {
            $user = Auth::user();
            $guardian = Guardian::find($user->foreign_id);
            $token =  $user->createToken('AligarhApp', ['guardian'])->accessToken;


            return response()->json([
                'User' => [
                    'User' => $user,
                    'Profile' => $guardian,
                ],
                'token' => $token
            ], $this->successStatus);
        }

        return response()->json([
            'error' => 'unauthorized',
            'msg' => 'Invalid UserID or Password'
        ], 401);
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function Logout(Request $request)
    {
        $this->validate($request, [
            'token' =>  'required'
        ]);
        $request->user()->token()->delete();
        return response()->json(['msg' => "Logout"]);
        //        Auth::user()->AauthAcessToken()->delete();
    }

    protected function ValidateLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                $this->LoginUserIDKey => 'required|min:4|max:255',
                'password' => 'required|min:6|max:12',
                'user_type' => 'required'
            ],
            [
                $this->LoginUserIDKey . '.required' => 'UserID is Required',
                'password.required' => 'Password is Required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        return true;
    }
}
