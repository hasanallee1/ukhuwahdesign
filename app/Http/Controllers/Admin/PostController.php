<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['permission:posts.index|posts.create|posts.edit|posts.delete']);
    }

    public function index()
    {
        if (request()->ajax()) {

            //get logged user
            $user = auth()->user();

            $model = Post::join('categories', 'posts.category_id', '=', 'categories.id')
                ->select('posts.id as id', 'posts.title as title', 'posts.slug as slug', 'posts.description as description', 'posts.image as image', 'categories.category as category')
                ->where('posts.user_id', auth()->user()->id);

            return DataTables::eloquent($model)

                ->addIndexColumn()
                ->addColumn('image', function ($a) {
                    $url = asset('storage/post-image/' . $a->image);
                    return '<img src="' . $url . '" class="img-thumbnail" height="15%" alt="image">';
                })

                ->addColumn('action', function ($row) use ($user) {

                    $btn = '';

                    if ($user->can('posts.edit')) {

                        $btn = $btn . '<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '"  class="btn btn-sm btn-outline-success edit"><i class="fa fa-edit"></i> Edit</a>';
                    }

                    if ($user->can('posts.delete')) {

                        $btn = $btn . '&nbsp; &nbsp; &nbsp;<a href="javascript:void(0)" id="' . $row->id . '" data-id="' . $row->id . '" class="btn btn-sm btn-outline-danger delete"><i class="fa fa-trash"></i> Hapus</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['image', 'action'])
                ->make();
        }

        return view('admin.post.index', [
            'title' => 'Post',
            'category' => Category::orderBy('category', 'asc')->get()
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

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'description' => 'required',
            'image' => 'required|image|file|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/post-image', $image->hashName());

        // create post
        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'title' => $request->title,
            'description' => $request->description,
            'image' => $image->hashName(),
            'category_id' => $request->category,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        $id = $request->id;

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Post',
            'data' => $post->find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $imageName = '';

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'image|file|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($request->post_id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = $image->hashName();

            if ($post->image) {
                Storage::disk('local')->delete('public/post-image/' . $post->image);
                // Storage::delete($post->image);
            }
            $image->storeAs('public/post-image', $imageName);
        } else {
            $imageName = $request->old_image;
        }


        // update category
        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'image' => $imageName,
            'description' => $request->description,
            'category_id' => $request->category,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $post = Post::findOrFail($request->id);

        if ($post->image) {
            Storage::disk('local')->delete('public/post-image/' . $post->image);
        }

        $post::destroy($post->id);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function getCategories(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $categories = Category::orderBy('category', 'asc')->select('id', 'category')->limit(5)->get();
        } else {
            $categories = Category::orderBy('category', 'asc')->select('id', 'category')->where('category', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $response = array();

        foreach ($categories as $category) {
            $response[] = array(
                'id' => $category->id,
                'text' => $category->category
            );
        }

        return response()->json($response);
    }
}
