<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;
class SubaccountController extends Controller
{

    private $folder = "admin.subaccounts.";


    public function index()
    {
        $subaccounts = User::where('role', 'subadmin')->get();
        return view($this->folder . 'index', compact('subaccounts'));
    }

    public function create()
    {
        return view($this->folder . 'create');
    }

    public function store(Request $request)
    {
        $user = new User;
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $permission = $user->setPermissions($request->all());
        $permission = implode(',', $permission);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'subadmin';
        $data['parent_id'] = auth()->id();
        $data['permission'] = 'none';
        $data['permissions'] = $permission;
        $data['is_active'] = 1;

        User::create($data);
        return redirect()->route('subaccounts.index')->with('success', 'Subcuenta creada exitosamente.');
    }

    public function edit(User $subaccount)
    {
        $permissions = explode(",", $subaccount->permissions);

        // return response()->json([
        //     'data' => $subaccount,
        //     'permissions' => $permissions
        // ]);

        return view($this->folder . 'edit', compact('subaccount', 'permissions'));
    }

    public function update(Request $request, User $subaccount)
    {
        $user = new User;
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $subaccount->id,
        ]);



        if ($request->password != null && !Hash::check($request->password, $subaccount->password)) {
            $request->validate([
                'password' => 'required|min:6',
            ]);

            $permission = $user->setPermissions($request->all());
            $permission = implode(',', $permission);

            $subaccount->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'permissions' => $permission
            ]);



        } else {
            $permission = $user->setPermissions($request->all());
            $permission = implode(',', $permission);

            $subaccount->update([
                'name' => $request->name,
                'email' => $request->email,
                'permissions' => $permission
            ]);
        }


        return redirect()->route('subaccounts.index')->with('success', 'Subcuenta actualizada.');
    }

    public function destroy(User $subaccount)
    {
        $subaccount->delete();
        return redirect()->route('subaccounts.index')->with('success', 'Subcuenta eliminada.');
    }
}
