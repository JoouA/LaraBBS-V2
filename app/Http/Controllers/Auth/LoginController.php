<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {

        $rules = [
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ];

        $messages = [
            'user.required' => '用户名不能为空',
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '验证码错误',
        ];

        $this->validate($request,$rules,$messages);
    }


    protected function attemptLogin(Request $request)
    {
       $field =  filter_var($request->get('username'),FILTER_VALIDATE_EMAIL)? 'email' : 'name';

        $data = [
            $field => $request->get('username'),
            'password' => $request->get('password'),
        ];

        return $this->guard()->attempt($data, $request->filled('remember')
        );
    }


    /**
     * @return string
     */
    public function username()
    {
        return 'username';
    }
}
