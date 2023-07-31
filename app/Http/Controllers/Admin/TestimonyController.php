<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Testimony;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TestimonyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['permission:categories.index|categories.create|categories.edit|categories.delete']);
    }

    public function index()
    {
        if (request()->ajax()) {

            // get data testimony
            $testimonies = Testimony::all();

            //get logged user
            $user = auth()->user();

            return DataTables::of($testimonies)
                ->addColumn('action', function ($row) use ($user) {

                    $btn = '';

                    if ($user->can('testimonies.edit')) {

                        $btn = $btn . '<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '"  class="btn btn-sm btn-outline-success edit"><i class="fa fa-edit"></i> Edit</a>';
                    }

                    if ($user->can('testimonies.delete')) {

                        $btn = $btn . '&nbsp; &nbsp; &nbsp;<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '" class="btn btn-sm btn-outline-danger delete"><i class="fa fa-trash"></i> Hapus</a>';
                    }

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.testimony.index', [
            'title' => 'Testimoni'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'name' => 'required',
            'jobs' => 'required',
            'testimony' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create testimony
        Testimony::create([
            'name' => $request->name,
            'jobs' => $request->jobs,
            'testimony' => $request->testimony
        ]);

        //return response
        return response()->json(
            [
                'success' => true,
                'message' => 'data berhasil disimpan !'
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Testimony $testimony)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail data testimoni',
            'data' => $testimony
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimony $testimony)
    {
        $validator  = Validator::make($request->all(), [
            'name' => 'required',
            'jobs' => 'required',
            'testimony' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create testimony
        $testimony->update([
            'name' => $request->name,
            'jobs' => $request->jobs,
            'testimony' => $request->testimony
        ]);

        //return response
        return response()->json(
            [
                'success' => true,
                'message' => 'data berhasil diupdate !'
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimony $testimony)
    {
        $testimony::destroy($testimony->id);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',

        ]);
    }
}
