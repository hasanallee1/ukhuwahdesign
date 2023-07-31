<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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

            //get data
            $categories = Category::all();

            //get logged user
            $user = auth()->user();

            return DataTables::of($categories)
                ->addColumn('action', function ($row) use ($user) {

                    $btn = '';

                    if ($user->can('categories.edit')) {

                        $btn = $btn . '<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '"  class="btn btn-sm btn-outline-success edit"><i class="fa fa-edit"></i> Edit</a>';
                    }

                    if ($user->can('categories.delete')) {

                        $btn = $btn . '&nbsp; &nbsp; &nbsp;<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '" class="btn btn-sm btn-outline-danger delete"><i class="fa fa-trash"></i> Hapus</a>';
                    }

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make();
        }
        return view('admin.category.index', [
            'title' => 'kategori'
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
        //define validator

        $validator = Validator::make($request->all(), [
            'category' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create category
        $category = Category::create([
            'category' => $request->category,
            'slug' => Str::slug($request->category, '-')
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Pos',
            'data' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create category
        $category->update([
            'category' => $request->category,
            'slug' => Str::slug($request->category, '-')
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate!'
        ]); //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category::destroy($category->id);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
