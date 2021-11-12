<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('blog.index')->with('posts', Post::orderBy('updated_at', 'DESC')->get());
    }


    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048'
        ]);

        $newImage = uniqid() . '-' . $request->title . '-' . $request->image->extension();
        $request->image->move(public_path('images'), $newImage);

        //we pass only 3 params  to "createslug" function , [modelname , column , title]
        $slug = SlugService::createSlug(Post::class, 'slug', $request->title);
        //end slug declaration

        Post::create([
            'title' =>  $request->input('title'),
            'description' =>   $request->input('description'),
            'slug' =>  $slug,
            'image_path' => $newImage,
            'user_id' =>  auth()->user()->id

        ]);
        return redirect('/blog')->with('message', 'Yout post has been added!');
    }

    public function show($id)
    {
    }



    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
