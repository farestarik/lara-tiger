<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

// Get Size Of File

if(!function_exists('getSize')){

    function getSize($file_path = '', $disk = null, $trans = true){
        $size = 0;
        if($file_path){
            if($disk == null){

                $size = Storage::size($file_path);

            }else{

                $size = Storage::disk($disk)->size($file_path);

            }
            if($trans == true){
                $size = bytesToHuman($size);
            }
        }

        return $size;
    }

}


// Get Invoice Status

if(!function_exists('invoice_status_name')){
    function invoice_status_name($num){
        if($num){
            $status = '';
            if($num == 0){
                $status = __("site.not_paid");
            }else if($num == 1){
                $status = __("site.full_paid");
            }else if($num == 2){
                $status = __("site.part_paid");
            }
            return $status;
        }
    }
}



// Get Selections Depends On Organizations

if(!function_exists('selections')){

    function selections($model){
            return $model::where('active', 1)->get();
    }

}


// Remove NULL Filters

if(!function_exists('remove_null_filters')){

    function remove_null_filters($data){

        foreach($data as $key => $value){

            if(key_exists('_token', $data)){
                unset($data['_token']);
            }

            if(is_null($value)){
                unset($data[$key]);
            }

            if(is_array($value)){
                foreach($value as $k => $v){
                    if(is_null($v)){
                        unset($data[$key][$k]);
                    }
                }
            }

            if(is_array($value)){
                if(empty($data[$key])){
                    unset($data[$key]);
                }
            }

        }

        return $data;

    }

}



// Get User ID

if(!function_exists('id')){

    function id($username = null){
        if(is_null($username)){
            return auth()->user()->id;
        }else{
            $user = User::firstWhere("username", $username);
            return $user->id;
        }
    }

}

// Verify User Role

if(!function_exists('hasRole')){

    function hasRole($role = 'user'){
        return auth()->user()->hasRole($role);
    }

}


// Verify User Permission

if(!function_exists('hasPermission')){

    function hasPermission($permission = null){
        if($permission){
            return auth()->user()->hasPermission($permission);
        }else{
            return NULL;
        }
    }

}



// Get User Token

if(!function_exists('my_token')){

    function my_token($user_id = null){
        if($user_id){
            return User::findOrFail($user_id)->token;
        }else{
            return auth()->user()->token;
        }
    }

}


// Get User Name

if(!function_exists('my_name')){

    function my_name($user_id = null){
        if($user_id){
            return User::findOrFail($user_id)->name;
        }else{
            return auth()->user()->name;
        }
    }

}





if(!function_exists('unset_prevented_perms'))
{
    function unset_prevented_perms($perms, $prevents = null){
    if(!is_null($prevents)):
        foreach($prevents as $model => $actions){
            dd($actions);
            foreach($actions as $action){
                if(($key = array_search($action , $perms[$model])) !== false){
                    unset($perms[$model][$key]);
                }
            }
        }
    endif;

        return $perms;
    }
}


if(!function_exists('clear_cache')){

    function clear_cache(){
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
    }

}

