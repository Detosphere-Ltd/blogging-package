<?php

namespace DetosphereLtd\BlogPackage\Http\Controllers;
use EditorJS\EditorJS;

use DetosphereLtd\BlogPackage\Models\Post;
use DetosphereLtd\BlogPackage\Transformers\PostTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Support\Arr;
use Illuminate\Pipeline\Pipeline;

class PostController extends Controller {
    public function __construct()
    {
        //
    }

    public function index()
    {
        $paginator = app(Pipeline::class)
            ->send(Post::query())
            ->thenReturn()
            ->paginate();

        $posts = $paginator->getCollection();

        $fractal = fractal()->collection($posts, new PostTransformer)
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Successfully returned all posts.',
                'data' =>  Arr::except($fractal, 'meta'),
                'meta' => $fractal['meta'],
            ]);
        }
    }

    public function store()
    {
        // Deny if unauthenticated.
        if (! auth()->check()) {
            abort (403, 'Only authenticated users can create new posts.');
        }

        try {
            // Validation
            request()->validate([
                'title' => 'required',
                'content' => 'required',
            ]);

            $data = request()->input('content');

            // configuration must be a JSON object
            // https://github.com/editor-js/editorjs-php#configuration-file
            $configuration = file_get_contents(config('blogpackage.editorjs_configuration'));

            // Initialize Editor backend and validate structure
            $editor = new EditorJS($data, $configuration);

            // dd($editor->getBlocks());

            // Authenticated user is author
            $author = auth()->user();

            $post = $author->posts()->create([
                'title'     => request('title'),
                'content'     => request('content'),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Post created.',
                    'data' => $post
                ], 201);
            }
        } catch (\Throwable $th) {
            throw $th;
        }        
    }

    public function show(Post $post)
    {
        return response()->json([
            'message' => 'Successfully returned post.',
            'data' => fractal()->item($post, new PostTransformer),
        ]);
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}