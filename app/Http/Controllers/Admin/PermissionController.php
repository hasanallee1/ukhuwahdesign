<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:permissions.index']);
    }

    public function index()
    {
        if (request()->ajax()) {

            // get data 
            $permissions = Permission::all();
            return DataTables::of($permissions)
                ->addIndexColumn()
                ->make();
        }

        return view('admin.permission.index', [
            'title' => 'Data Permission'
        ]);
    }
}
