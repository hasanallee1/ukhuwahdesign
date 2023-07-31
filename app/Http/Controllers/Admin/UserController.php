<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:users.index|users.create|users.edit|users.delete']);
    }

    public function index()
    {
        if (request()->ajax()) {

            //get data
            $users = User::with('roles');

            //get logged user
            $user = auth()->user();

            return DataTables::eloquent($users)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    $btn = '';
                    // dd($roles->getPermissionNames());
                    foreach ($user->getRoleNames() as $role) {
                        $btn = '<label class="badge badge-success">' . $role . '</label>'  . ' ' . $btn;
                    }

                    return $btn;
                })

                ->addColumn('action', function ($row) use ($user) {

                    $btn = '';

                    if ($user->can('users.edit')) {

                        $btn = $btn . '<a href="/admin/user/' . $row->id . ' /edit" id="' . $row->id . '" data-id="' . $row->id . '"  class="btn btn-sm btn-outline-success edit"><i class="fa fa-edit"></i> Edit</a>';
                    }

                    if ($user->can('users.delete')) {

                        $btn = $btn . '&nbsp; &nbsp; &nbsp;<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '" class="btn btn-sm btn-outline-danger delete"><i class="fa fa-trash"></i> Hapus</a>';
                    }

                    return $btn;
                })

                ->rawColumns(['action', 'role'])
                ->make();
        }
        return view('admin.user.index', [
            'title' => 'User',
        ]);
    }

    public function create()
    {
        return view('admin.user.create', [
            'title' => 'Tambah User',
            'roles' => Role::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        //define validator
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        //assign role
        $user->assignRole($request->roles);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!'
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', [
            'title' => 'Edit User',
            'roles' => Role::latest()->get(),
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        //define validator
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $user->id,
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::findOrFail($user->id);

        // update user

        if ($request->password == '') {
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,

            ]);
        } else {
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password)
            ]);
        }



        //assign role
        $user->syncRoles($request->roles);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!'
        ]);
    }

    public function destroy(User $user)
    {
        $user::destroy($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function showProfile($id)
    {

        // echo 'HI';

        $user = User::find($id);
        dd($user->name);

        // return view('admin.user.showProfile', [
        //     'user' => $user
        // ]);
    }
}
