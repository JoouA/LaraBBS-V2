<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Overtrue\Socialite\SocialiteManager;

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
        if (filter_var($request->get('username'),FILTER_VALIDATE_EMAIL)){
            $field = 'email';
        }elseif (strlen($request->get('username')) == 11){
            $field = 'mobile';
        }else{
            $field = 'name';
        }

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

    public function redirectToProvider()
    {
        $config = config('services');

        $socialite = new SocialiteManager($config);;

        return  $socialite->driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        $config = config('services');

        $socialite = new SocialiteManager($config);

        $user = $socialite->driver('github')->user();

        $user_info = [
            'name' => $user->getUsername(),
            'email'=> $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'password' => bcrypt(str_random(16)),
        ];

        $is_user_exit = User::query()->where('email',$user_info['email'])->first();

        if ($is_user_exit){
            try{
                \Auth::login($is_user_exit);
                return redirect()->route('root')->with('success','登录成功');
            }catch (\Exception $e){
                return  redirect()->route('login')->with('danger','登录失败');
            }
        }else{
            $user = User::create($user_info);
            try{
                \Auth::login($user);
                return redirect()->route('root')->with('success','登录成功');
            }catch (\Exception $e){
                return back()->with('danger','登录失败');
            }

        }

        return $user;
    }
}
