<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Profile;
use Dirape\Token\Token;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PrintFiltersService;

class AdminController extends Controller
{

    public function __construct(){
        $this->middleware(['permission:create_admins'])->only('create');
        $this->middleware(['permission:read_admins'])->only('index');
        $this->middleware(['permission:update_admins'])->only('edit');
        $this->middleware(['permission:delete_admins'])->only('destroy');
    }

    public $paginate_num = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $owner = auth()->user()->hasRole("owner");

        $filters = remove_null_filters($request->all());

        if($owner){

            if($request->all()){
                $admins = User::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("username", "like", "%".$request->search."%");
                    $query->orWhere("email", "like", "%".$request->search."%");
                 })
                 ->whereRoleIs("admin")
                 ->orWhereRoleIs("owner")
                 ->filterBy($filters);
            }else{
                $admins = User::whereRoleIs("admin")->orWhereRoleIs('owner');
            }


        }else{

            if($request->all()){
                $admins = User::where(function($query) use ($request){
                    $query->where("name", "like", "%".$request->search."%");
                    $query->orWhere("username", "like", "%".$request->search."%");
                    $query->orWhere("email", "like", "%".$request->search."%");
                })
                 ->whereRoleIs("admin")
                 ->where("created_by",auth()->user()->id)
                 ->filterBy($filters);
            }else{
                $admins = User::whereRoleIs("admin")
                ->where("created_by",auth()->user()->id);
            }

        }


        if(!$admins){
            return abort(500);
        }

          // PRINT FILTERD DATA

        //   if(isset($request->print)){
        //     if($request->print == 1){
        //         $admins = $admins->get();
        //         return PrintFiltersService::print($admins, 'dashboard.admins.reports.index', 'header', __("site.admins_report"));

        //     }
        // }

        $admins = $admins->paginate($this->paginate_num);

        return view("dashboard.admins.index")
        ->with("admins", $admins);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $perms = [];
        $models = [];

        foreach($permissions as $permission){
            $name = $permission->name;
            $name = explode("_", $name);
            $map = $name[0];
            $model = $name[1];
            $perms[$model][] = $map;
            $models[] = $model;
        }

        $models = array_unique($models);
        $models = array_truncate($models);

        $perms = unset_prevented_perms($perms,[]);

        $models = [
            'users','admins','settings','profile'
        ];

        return view("dashboard.admins.create")
        ->with("perms", $perms)
        ->with("models", $models);
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique("users", "username")
            ],
            'email' => [
                'required',
                Rule::unique("users", "email")
            ],
            'password' => 'required|confirmed'
        ]);

        $data = $request->except(["password","password_confirmation", "permissions"]);
        $data['token'] = (new Token)->Unique('users', 'token', 17 );
        $data['password'] = bcrypt($request->password);
        $data['created_by'] = id();


        DB::transaction(function () use($data,$request) {

            $admin = User::create($data);

            if(!$admin){
                return abort(500, __("site.contact_support"));
            }

            Profile::create([
                'user_id' => $admin->id
            ]);

            $admin->attachRole("admin");

            if(!empty($request->permissions)){
                $admin->syncPermissions($request->permissions);
            }

        });

        session()->put('success',__('site.added_successfully'));

        return redirect()->route('dashboard.admins.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $permissions = Permission::all();
        $perms = [];
        $models = [];

        foreach($permissions as $permission){
            $name = $permission->name;
            $name = explode("_", $name);
            $map = $name[0];
            $model = $name[1];
            $perms[$model][] = $map;
            $models[] = $model;
        }

        $models = array_unique($models);
        $models = array_truncate($models);

        return view("dashboard.admins.edit",compact('admin'))
        ->with("perms", $perms)
        ->with("models", $models);
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $admin)
    {
        $this->validate($request,[
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique("users")->ignore($admin->id)
            ],
            'email' => [
                'required',
                Rule::unique("users")->ignore($admin->id)
            ]
        ]);

        $data = $request->except(["password","password_confirmation", "permissions"]);
        if($request->password && $request->password !== ""){
            $data['password'] = bcrypt($request->password);
        }

        if(!$admin->update($data)){
            return abort(500, __("site.contact_support"));
        }

        if(!empty($request->permissions)){
            $admin->syncPermissions($request->permissions);
        }else{
            $admin->detachPermissions();
        }

        clear_cache();

        session()->put('success',__('site.updated_successfully'));

        return redirect()->route('dashboard.admins.edit', $admin->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $admin)
    {
        if(!$admin->delete()){
            abort(500);
        }

        session()->put('success', __("site.deleted_successfully"));

        return redirect()->route("dashboard.admins.index");
    }

}
