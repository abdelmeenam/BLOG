<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {

        return view('blog.index')
            ->with('posts', Post::orderBy('updated_at', 'DESC')->get());
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
            'image' => 'required'
        ]);

        $newImage  = time() . "." . $request->image->getClientOriginalExtension();
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
        return redirect('/blog')
            ->with('message', 'Yout post has been added!');
    }

    public function show($slug)
    {
        return view('blog.show')
            ->with('post', Post::where('slug', $slug)->first());
    }


    public function edit($slug)
    {
        return view('blog.edit')
            ->with('post', Post::where('slug', $slug)->first());
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $post = post::where('slug', $slug)->first();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->slug = $request->slug;

        if ($image = $request->file('image')) {
            $newImage  = time() . "." . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $newImage);
            $post->image_path = "$newImage";
        } else {
            unset($post->image);
        }

        $post->update();
        return redirect('/blog')
            ->with('message', 'Yout post has been UPDATED!');
    }

    public function destroy($slug)
    {
        $post = Post::where('slug', $slug);
        $post->delete();
        return redirect('/blog')
            ->with('message', 'Yout post has been Deleted!');
    }
}
