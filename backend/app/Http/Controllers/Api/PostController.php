<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use illuminate\Support\Facades\Storage;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'konten' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('posts', 'public');
    }

    $post = Post::create([
        'nama' => $request->nama,
        'konten' => $request->konten,
        'image' => $imagePath,
    ]);

    return response()->json($post, 201);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'konten' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }
        // Simpan gambar baru
        $imagePath = $request->file('image')->store('posts', 'public');
        $post->image = $imagePath;
    }

    $post->nama = $request->nama;
    $post->konten = $request->konten;
    $post->save();

    return response()->json($post);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
{
    if ($post->image && Storage::disk('public')->exists($post->image)) {
        Storage::disk('public')->delete($post->image);
    }
    $post->delete();

    return response()->json(['message' => 'Post deleted successfully']);
}
}
