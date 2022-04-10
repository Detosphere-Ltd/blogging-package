<?php

namespace DetosphereLtd\BlogPackage\Http\Controllers;

use Carbon\Carbon;
use EditorJS\EditorJS;

use DetosphereLtd\BlogPackage\Models\Post;
use DetosphereLtd\BlogPackage\Rules\ProperDateTime;
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
                'excerpt' => 'string|nullable|max:300',
                'publish' => 'required|boolean',
                // TODO: validate the date
                'publishing_at' => [
                    'required_if:publish,true',
                    new ProperDateTime,
                    // TODO write a rule to ensure that date is after or equal to now. Cant use the default laravel rule because it doesnt support UNIX
                ],
                'content' => 'required',
            ]);

            $data = json_encode(request()->input('content'));

            // configuration must be a JSON object
            // https://github.com/editor-js/editorjs-php#configuration-file
            $configuration = file_get_contents(config('blogpackage.editorjs_configuration'));

            // Initialize Editor backend and validate structure
            $editor = new EditorJS($data, $configuration);

            // dd($editor->getBlocks());

            $scheduledFor = request()->has('publishing_at') ? Carbon::parse(request('publishing_at')) : null;
            $publishedAt = null;

            // Authenticated user is author
            $author = auth()->user();
            
            // if publish is true and scheduledFor is less than now, then set published_at
            if ($scheduledFor !== null) {
                // dd(request()->publish);
                if (request()->publish == true && $scheduledFor->lessThanOrEqualTo(now())) {
                    $publishedAt = $scheduledFor;
                    $scheduledFor = null;
                }
            }

            // a draft is any unpublished document
            if ($scheduledFor === null && $publishedAt === null) {
                $isDraft = true;
            } else {
                $isDraft = false;
            }

            $post = $author->posts()->create([
                'title'     => request('title'),
                'content'     => $data,
                'scheduled_for' => $scheduledFor,
                'published_at' => $publishedAt,
                'is_draft' => $isDraft,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Post created.',
                    'data' => fractal()->item($post, new PostTransformer)
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