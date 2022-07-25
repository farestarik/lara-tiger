<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request, $profile_id = null)
    {
        if(!is_null($profile_id)){
            $profile = Profile::findOrFail($profile_id);
        }else{
          $user_id = auth()->user()->id;
          $profile = Profile::where('user_id',$user_id)->firstOrFail();
        }


        return view("dashboard.profile.index", compact("profile"));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $old_img = $profile->photo;

        if(request()->has('img')){
            $photo = $request->img;
            $photo_file = time() . '.' . $photo->getClientOriginalExtension();
            if($photo->move('uploads/profiles', $photo_file)){
                if($old_img !== 'default.png'){
                    Storage::disk("public_uploads")->delete("profiles/".$old_img);
                }
            }
            $request['photo'] = $photo_file;
        }

        if(isset($request->password)){
            $password = request('password');
            if($password !== ''){
                $user_id = $profile->user_id;
                $user = User::firstWhere('id', $user_id);
                try{
                    $user->update([
                        'password' => bcrypt($password)
                    ]);
                }catch(Throwable $error){
                    session()->put("error", $error);
                }
            }
        }

        $profile->update($request->except(["img", "password", "password_confirmation"]));

        session()->put('success', __("site.updated_successfully"));

        return view("dashboard.profile.index", compact("profile"));
    }
}
