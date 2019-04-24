<?php

namespace RBAC\Controllers;

use RBAC\Role;
use RBAC\RoleBinding;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RoleBindingController
{
    use ValidatesRequests;

    public function index()
    {
        $roleBindings = RoleBinding::with(['role', 'subjects'])->get();

        return view('rbac::role-bindings.index', compact('roleBindings'));
    }

    public function create()
    {
        $roles = Role::get();

        return view('rbac::role-bindings.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'namespace' => 'required',
            'name' => 'required',
            'role_kind' => 'required',
            'role_name' => 'required',
        ]);

        RoleBinding::create($request->all());

        return redirect()->route('role-bindings.index');
    }

    public function show(RoleBinding $roleBinding)
    {
        return view('rbac::role-bindings.show', compact('roleBinding'));
    }

    public function edit(RoleBinding $roleBinding)
    {
        $roles = Role::get();

        return view('rbac::role-bindings.edit', compact('roleBinding', 'roles'));
    }

    public function update(Request $request, RoleBinding $roleBinding)
    {
        $this->validate($request, [
            'namespace' => 'required',
            'name' => 'required',
            'role_kind' => 'required',
            'role_name' => 'required',
        ]);

        $roleBinding->update($request->all());

        return redirect()->route('role-bindings.index');
    }

    public function destroy(RoleBinding $roleBinding)
    {
        $roleBinding->delete();

        return redirect()->route('role-bindings.index');
    }
}