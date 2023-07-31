<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:roles.index|roles.create|roles.edit|roles.delete']);
    }


    public function index()
    {
        if (request()->ajax()) {


            //get user data
            $user = auth()->user();

            // get data 
            $roles = Role::with('permissions');

            // dd($roles->getPermissionNames());

            return DataTables::eloquent($roles)

                ->addColumn('permission', function ($roles) {
                    $btn = '';
                    // dd($roles->getPermissionNames());
                    foreach ($roles->getPermissionNames() as $role) {
                        $btn = '<button type="button" class="btn btn-sm btn-outline-primary mr-1 mb-1 mt-1">' . $role . '</button>'  . ' ' . $btn;
                    }

                    return $btn;
                })

                ->addColumn('action', function ($row) use ($user) {

                    $btn = '';

                    if ($user->can('roles.edit')) {

                        $btn = $btn . '<a href="/admin/role/' . $row->id . ' /edit" id="' . $row->id . '" data-id="' . $row->id . '"  class="btn btn-sm btn-outline-success edit"><i class="fa fa-edit"></i> Edit</a>';
                    }

                    if ($user->can('roles.delete')) {

                        $btn = $btn . '&nbsp; &nbsp; &nbsp;<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '" class="btn btn-sm btn-outline-danger delete"><i class="fa fa-trash"></i> Hapus</a>';
                    }

                    return $btn;
                })

                ->rawColumns(['permission', 'action'])
                ->make();
        }

        // $roles = Role::latest();

        // dd($roles->getPermissionNames());

        return view('admin.role.index', [
            'title' => 'Data Role',
            'permissions' => Permission::latest()->get()
        ]);
    }

    public function create()
    {
        return view('admin.role.create', [
            'title' => 'Tambah Role',
            'permissions' => Permission::latest()->get()
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create role
        $role = Role::create([
            'name' => $request->name
        ]);

        //assign permission to role
        $role->syncPermissions($request->input('permissions'));

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!'
        ]);
    }

    public function edit(Role $role)
    {
        return view('admin.role.edit', [
            'title' => 'Edit Role',
            'permissions' => Permission::latest()->get(),
            'role' => $role
        ]);
    }

    public function show(Role $role)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Pos',
            'data' => $role,
            'role_permissions' => $role->permissions()->get(),
            'permissions' => Permission::latest()->get()
        ]);
    }

    public function update(Request $request, Role $role)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $role->id
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $role = Role::findOrFail($request->role_id);

        // create category
        $role->update([
            'name' => $request->name
        ]);

        //assign permission to role
        $role->syncPermissions($request->input('permissions'));

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate!'
        ]); //
    }

    public function destroy(Role $role)
    {
        $role = Role::findOrFail($role->id);
        $permissions = $role->permissions;
        $role->revokePermissionTo($permissions);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
