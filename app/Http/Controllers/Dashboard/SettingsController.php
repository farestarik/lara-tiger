<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:create_settings'])->only('create');
        $this->middleware(['permission:read_settings'])->only('index');
        $this->middleware(['permission:update_settings'])->only('edit');
        $this->middleware(['permission:delete_settings'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $setting = Settings::first();

        return view('dashboard.settings.index', compact('setting'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $setting = Settings::findOrFail($id);

        $old_img = $setting->logo;

        if(request()->has('img')){
            $photo = $request->img;
            $photo_file = time() . '.' . $photo->getClientOriginalExtension();
            if($photo->move('pics', $photo_file)){
                if($old_img !== 'default.png'){
                    Storage::disk("public_folder")->delete("pics/".$old_img);
                }
            }
            $request['logo'] = $photo_file;
        }


        $setting->update($request->except(["img"]));

        session()->put('success', __("site.updated_successfully"));

        return \redirect()->route('dashboard.settings.index');
    }
}
