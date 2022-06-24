<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::all()->except(Auth::id());
        $roles = Role::all();
        $data = [
            'users'  => $users,
            'roles'   => $roles
        ];
        return view('admin.users')->with($data);
    }

    public function searchUsers(Request $request)
    {
        $data = $request->only('search');

        $users = User::where('name', 'like', "%{$data['search']}%")
            ->orWhere('email', 'like', "%{$data['search']}%")
            ->get()
            ->except(Auth::id());
        $roles = Role::all();
        $data = [
            'users'  => $users,
            'roles'   => $roles
        ];
        return view('admin.users')->with($data);
    }

    public function updateRole(Request $request) 
    {
        $request->validate([
            'userId' => 'required',
            'newRoleId' => 'required'
        ]);

        $data = $request->all();

        $user = User::find($data['userId']);
        $roles = $user->getRoleNames();

        //remove current user roles
        foreach($roles as $role) {
            $user->removeRole($role);
        }

        //add new user role
        $newRole = Role::find($data['newRoleId']);
        $user->assignRole($newRole->name);

        return redirect("users")->withSuccess('Role updated succesfully');
    }
}
