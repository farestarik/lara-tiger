<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

    //    User::whereId(1)->first()->attachPermissions([
    //         'create_settings',
    //         'read_settings',
    //         'update_settings',
    //         'delete_settings'
    //     ]);

        // dd(auth()->user()->permissions);

        $admins_count = User::whereRoleIs('admin')->orWhereRoleIs('owner')->get()->count();

        return view("dashboard.index",compact([
            'admins_count'
        ]));
    }
}
