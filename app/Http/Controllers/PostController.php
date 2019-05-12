<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Transformers\PostTransformer;

class PostController extends Controller
{
    public function add(Request $request, Post $post)
    {
        $this->validate($request, [
            'content' => 'min:10',
        ]);

        $post = $post->create([
            'user_id' => Auth::user()->id,
            'content' => $request->content
        ]);

        $response = fractal()
            ->item($post)
            ->transformWith(new PostTransformer)
            ->toArray();

        return response()->json($response, 201);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->content = $request->get('content', $post->content);
        $post->save();

        return fractal()
            ->item($post)
            ->transformWith(new PostTransformer)
            ->toArray();
    }

    public function delete(Request $request, Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post Delete Successfully!',
        ]);
    }
}
