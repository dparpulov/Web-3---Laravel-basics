<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;
use Illuminate\Support\Facades\Storage;

use PDF;

// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;


class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        //$this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //$posts = Post::orderBy('title', 'desc')->take(1)->get(); //returns only 1 post
        //$posts = Post::orderBy('title', 'desc')->get(); //returns all posts desc
        //return Post::where('title', 'Post Two') -> get(); //returns the post with given name
        $posts = Post::orderBy('created_at', 'desc')->paginate(10); //after 10 objects makes a page

        //$posts = DB::select('SELECT * FROM posts');
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999',
            'cover_image_pixelated' => 'image|nullable|max:1999'
        ]);
            
        //Handle file upload
        if($request->hasFile('cover_image')){
            //The image
            $image = $request->file('cover_image');

            //Get filename with the extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            
            //Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            //$watermark =  Image::make('storage/watermark.jpg');
            //$image->insert('http://theapp/', 'bottom-right', 10, 10);
            //$image->insert($watermark, 'bottom-right', 10, 10);
            //$image->insert('storage/watermark.jpg', 'bottom-right', 10, 10);

            

            //Upload pixelated copy of the image
            $fileNameToStorePixelated = $filename.'_pixelated'.time().'.'.$extension;
            $pathPixelated = public_path('storage/cover_images_pixelated/' . $fileNameToStorePixelated);
            Image::make($image)->pixelate(12)->save($pathPixelated);
        }else{
            $fileNameToStore = 'noimage.jpg';
            $fileNameToStorePixelated = 'noimage.jpg';
        }

        //Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->cover_image_pixelated = $fileNameToStorePixelated;
        //Upload image
        $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        //Check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unauthorised page');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999',
            'cover_image_pixelated' => 'image|nullable|max:1999'
        ]);

        //Handle file upload
        if($request->hasFile('cover_image')){
            //The image
            $image = $request->file('cover_image');

            $image->insert('http://theapp/', 'bottom-right', 10, 10);

            //Get filename with the extension
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();
            
            //Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            //Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            //Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);


            $fileNameToStorePixelated = $filename.'_pixelated'.time().'.'.$extension;
            $pathPixelated = public_path('storage/cover_images_pixelated/' . $fileNameToStorePixelated);
            Image::make($image)->pixelate(12)->save($pathPixelated);

        }

        //Update post
        $post = Post::find($id);
        $post ->title = $request->input('title');
        $post ->body = $request->input('body');
        if($request->hasFile('cover_image')){
            if($post->cover_image != 'noimage.jpg') {
                Storage::delete('public/cover_images/'.$post->cover_image);
                Storage::delete('public/cover_images_pixelated/'.$post->cover_image_pixelated);
            }
            $post->cover_image = $fileNameToStore;
            $post->cover_image_pixelated = $fileNameToStorePixelated;
        }
        $post->save();

        return redirect('/home')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
       
        //Check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('/posts')->with('error', 'Unauthorised page');
        }

        if($post->cover_image != 'noimage.jpg'){
            //Delete image
            Storage::delete('public/cover_images/'.$post->cover_image);
            Storage::delete('public/cover_images_pixelated/'.$post->cover_image_pixelated);
        }
        
        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed');
    }


    public function downloadPDF($id)
    {
        $post = Post::find($id);

        //$data = Post::get();
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('posts/pdf', compact('post'));

        return $pdf->download('post.pdf');
    }
 
}
