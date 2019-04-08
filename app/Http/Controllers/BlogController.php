<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog;
use App\Http\Resources\Blog as BlogResource;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::paginate(15);

        return BlogResource::collection($blogs);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $blog = $request->isMethod('put') ? blog::findOrfail
        ($request->blog_id) : new blog;

        $blog->id = $request->input('blog_id');
        $blog->title = $request->input('title');
        $blog->body = $request->input('body');

        if($blog->save()){
            return new BlogResource($blog);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get blog
        $blog = blog::findOrFail($id);

        //return single blog as a resource
        return new BlogResource($blog);

    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       // Get blog
       $blog = blog::findOrFail($id);

      if($blog->delete()){
       return new blogResource($blog);
      }
    }
}
