<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $posts = Post::all();

        if (! (count($posts) === 0)){
            return response()->json(['message' => 'Successfully get posts list', 'data' => $posts ],200);
        }else{
            return response()->json(['message' => 'Posts not found', 'data' => '' ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::find($id);
        if ($post){
            return response()->json(['message' => 'Successfully get post', 'data' => $post ],200);
        }else{
            return response()->json(['message' => 'post not found', 'data' => '' ],200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'title' => 'required|string|max:240',
            'short_content' => 'required|string|max:200',
            'content' => 'required|string',
            'cover' => 'required|mimes:jpeg,jpg,png'
        ]);

        if ($request->file('cover')){
            $filename = time().'_'.$request->file('cover')->getClientOriginalName();
            $request->file('cover')->storeAs('/public/posts/covers/',$filename);

            $data = [
              'title' => $request->title,
              'content' => $request->get('content'),
              'short_content' => $request->short_content,
              'cover' => $filename,
              'cover_url' => '/storage/posts/covers/'.$filename
            ];
            $post = Post::create($data);
        }


        return response()->json(['message' => 'Successfully created post', 'data' => $post ],201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request,[
            'title' => 'string|max:240',
            'short_content' => 'string|max:200',
            'content' => 'string',
            'cover' => 'mimes:jpeg,jpg,png'
        ]);

        $post = Post::find($id);

        if ($post){
                if($request->title){
                    $post->title = $request->title;
                }
                if($request->shor_content){
                    $post->short_content = $request->short_content;
                }
                if($request->get('content')){
                    $post->content = $request->get('content');
                }
                if($request->cover && $request->file('cover')){
                        $filename = time().'_'.$request->file('cover')->getClientOriginalName();
                        $request->file('cover')->storeAs('/public/posts/covers/',$filename);
                        Storage::delete('/public/posts/covers/'.$post->cover);
                        $post->cover = $filename;
                        $post->cover_url =  '/storage/posts/covers/'.$filename;
                }
                $post->save();
                return  response()->json(['message' => 'Successfully updated post', 'data' => $post ],200);
        }else{
            return response()->json(['message' => 'post not found', 'data' => '' ],200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request,int $id): JsonResponse
    {
        $post = Post::find($id);
        if ($post){
            $post->delete();
            return response()->json(["message" => 'Successfully deleted post'],200);
        }else{
            return response()->json(['message' => 'post not found' ],200);
        }
    }
}
