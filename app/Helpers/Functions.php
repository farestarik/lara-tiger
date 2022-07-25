<?php

// System Consts

define("DS", DIRECTORY_SEPARATOR);

// System Functions







// Sync Functions HasMany


if(!function_exists('syncMany')){

    function syncMany($parent_id, $parent_model, $given_data, $child_key, $child_model){

        $invoice = $parent_model::whereId($parent_id)->first();
        $invoice_products = $invoice->$child_key;
        $given_products_ids = collect($given_data)->keyBy('id');
        $given_products_ids = array_keys($given_products_ids->toArray());

        $exist_products_ids = collect($invoice_products)->keyBy('id');
        $exist_products_ids = array_keys($exist_products_ids->toArray());

        foreach($given_data as $index => $product){
            $product_exist = @$child_model::firstWhere('id', $product['id']);
            if($product['id'] !== null){
                $product_data = array_slice($product, 0, -1);
                $child_model::whereId($product['id'])->update($product_data);
            }else{
                unset($product['id']);
                $child_model::create($product);   
            }
        }

        foreach($exist_products_ids as $id){
            if(!in_array($id , $given_products_ids)){
                $child_model::whereId($id)->delete();
            }
        }
        
        return true;
    }

}



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



if(!function_exists('cruds')){
    function cruds($addons = ''){
        $default = 'c,r,u,d';
        if($addons !== ''){
            return $default . ',' . $addons;
        }
        return 'c,r,u,d';
    }
}


if(!function_exists('fname')){
    function fname($file_path = ''){
        if($file_path){
            $offset = strpos($file_path, '/');
            $name = substr($file_path, $offset + 1);
            return $name;
        }
    }
}


if(!function_exists('generateCode')){

    function generateCode($length = 5, $prefix = null){
        if(is_null($prefix)){ $prefix = ''; }
        $nums = '1234567890';
        $nums_length = strlen($nums);
        $code = '';
        for($i = 0; $i < $length; $i++){
            $code .= $nums[rand(0, $nums_length -1)];
        }
        return $prefix.$code;

    }

}


if(!function_exists('success_session')){
    function success_session($action = 'create'){
        $create = ['create','store','add'];
        $update = ['edit','update'];
        $delete = ['delete','destroy'];

        if(in_array($action, $create)){
            session()->put('success',__('site.added_successfully'));
        }

        if(in_array($action, $update)){
             session()->put('success',__('site.updated_successfully'));
        }

        if(in_array($action, $delete)){
             session()->put('success',__('site.deleted_successfully'));
        }

    }
}


if(!function_exists('dtstring')){
    function dtstring($date){
        return \Carbon\Carbon::parse($date)->toDayDateTimeString();
    }
}


if(!function_exists('del_all_files')){
    function del_all_files($files = '', $path = ''){
        if($files !== '' && $path !== ''){
            foreach($files as $file){
                unlink($path . '/'. $file);
            }
        }
    }
}


if(!function_exists('success_btn')){
    function success_btn($msg = 'None'){
        return '<button type="button" class="btn btn-success">'.$msg.'</button>';
    }
}

if(!function_exists('danger_btn')){
    function danger_btn($msg = 'None'){
        return '<button type="button" class="btn btn-danger">'.$msg.'</button>';
    }
}

if(!function_exists('warning_btn')){
    function warning_btn($msg = 'None'){
        return '<button type="button" class="btn btn-warning">'.$msg.'</button>';
    }
}


if(!function_exists('model_exists')){
    function model_exists($model, $id){
        if($model::where('id',$id)->exists()){
            return true;
        }else{
            return false;
        }
    }
}


function isHTML($string){
    return $string != strip_tags($string) ? true:false;
}

function dontMatch(array $given, $match){
    $match = [$match];
    $needle = array_diff($given, $match);
    $needle = array_values($needle);
    return $needle;
}

function bytesToHuman($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
}


if(!function_exists("file_ext")){
    function file_ext($path = null){
        if(!is_null($path)){
            $infoPath = pathinfo($path);
            $extension = $infoPath['extension'];
            return $extension;
        }
    }
}


if(!function_exists("str_limit"))
{
    function str_limit($str, $limit){
        return \Str::limit($str,$limit);
    }
}

function array_truncate(&$arr)
{
    return array_splice($arr, 0, count($arr));
}


function current_view(){
    $url = url()->current();
    $exploded = explode('/', $url);
    $params = array_keys($exploded);
    $params_count = count($params);

    if($params_count > 5){
        if($exploded[5] == 'index'){
            return "dashboard.index";
        }else{
            if($params_count > 6){
                return $exploded[4] . '.' . $exploded[5] . '.' . $exploded[6];
            }else{
                return $exploded[4] . '.' . $exploded[5] . '.' . 'index';
            }
        }
    }elseif($params_count == 5){
        return "dashboard.index";
    }
}



function pre_r($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}



function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



function slug($str, $unique = true){
    if($str){
        $exploded = explode(' ',$str);
        if($unique === true){
            $exploded = array_unique($exploded);
        }
        $slug = implode('-',$exploded);
        $slug = strtolower($slug);
        return $slug;
    }
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function checkArrayKeys($array,$needle){

    $nextToBeSet = $needle;
    $is_exist = false;
    foreach($array as $k => $v)
    {
        if(startsWith($k, $nextToBeSet))
        {
            $is_exist = true;
            break;
        }
    }

    if($is_exist){
        return true; // Exist
    }else{
        return false; // Not Exist
    }

}

function checkSession(){
    $sessions = session()->all();
    if(checkArrayKeys($sessions,'login') === false){
        return true; // Expired
    }
}

// Redirection Func

if(!function_exists('redirect_to')){

    function redirect_to($route = null){
        if($route){
            header("Location:".$route);
        }
    }

}


// dump Func

if(!function_exists('d')){

    function d($content){
        dump($content);
    }

}

// Generate Unique Token

if(!function_exists('generateToken')){
    function generateToken()
    {
        return md5(rand(1, 170) . microtime());
    }
}


// Generate Exam Token

if(!function_exists('generate_token')){

    function generate_token(){
        $token = md5(time());
        return $token;
    }

}

// Shuffle Array With Keeping Indexes

function shuffle_assoc($array)
{
    $orig = array_flip($array);
    shuffle($array);
    foreach($array AS $key=>$n)
    {
        $data[$n] = $orig[$n];
    }
    return array_flip($data);
}





















// Html


function br(){
    echo "<br>";
}

function space(){
    echo "&nbsp;";
}

function a($link = "link", $href = "#", $target = "", $class = "", $style = ""){
    echo "<a href='".$href."' class='".$class."' target='".$target."' style='".$style."'>{$link}</a>";
}

function alert($txt = '', $class = 'success'){
    echo "<div class='alert alert-".$class."'>".$txt."</div>";
}

function img($src,$class = '',$style = ''){
    if($src){
        echo "<img src='".$src."' class='".$class."' style='".$style."'>";
    }
}


function imgLink($src,$class = '',$style = '',$link = "#", $target="_blank",$download = ''){
    if($src){
        echo "<a href='".$link."' target='".$target."'><img src='".$src."' class='".$class."' style='".$style."' ".$download."></a>";
    }
}


function danger($txt = 'None'){
    echo "<b class='text-danger'>".$txt."</b>";
}

function success($txt = 'Ok'){
    echo "<b class='text-success'>".$txt."</b>";
}

function b($txt,$style){
    echo "<b class='text-default' style='".$style."'>".$txt."</b>";
}


function h1($txt, $class = '', $style = ""){
    echo "<h1 class='".$class."' style='".$style."'>".$txt."</h1>";
}

function h2($txt, $class = '', $style = ""){
    echo "<h2 class='".$class."' style='".$style."'>".$txt."</h2>";
}

function h3($txt, $class = '', $style = ""){
    echo "<h3 class='".$class."' style='".$style."'>".$txt."</h3>";
}

function h4($txt, $class = '', $style = ""){
    echo "<h4 class='".$class."' style='".$style."'>".$txt."</h4>";
}

function h5($txt, $class = '', $style = ""){
    echo "<h5 class='".$class."' style='".$style."'>".$txt."</h5>";
}

function h6($txt, $class = '', $style = ""){
    echo "<h6 class='".$class."' style='".$style."'>".$txt."</h6>";
}

