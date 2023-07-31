<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Testimony;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'title' => 'Dashboard',
            'jumlah_post' => Post::count(),
            'jumlah_kategori' => Category::count(),
            'jumlah_user' => User::count(),
            'jumlah_testimoni' => Testimony::count(),
        ]);
    }
}
