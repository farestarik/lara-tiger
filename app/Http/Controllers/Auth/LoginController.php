<?php

namespace App\Http\Controllers\Auth;

use App\Models\Profile;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $check_if_exist = \App\Models\User::firstWhere("username", $login);

        if($login){

            if($check_if_exist == null){
                return abort(500, "This User Is Not Exist In Our Records!");
            }

            if($check_if_exist->profile == null){
                   Profile::create([
                    'user_id' => $check_if_exist->id
                   ]);
            }


            $active = \App\Models\User::firstWhere("username", $login)->active;

            if($active == 0){
                return abort(500, "This Account Is DeActivated");
            }
        }



        $fieldType ='username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }


}
