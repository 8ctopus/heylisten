<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\AuthorizedRequest;
use App\Http\Requests\CreateCommentRequest;
use App\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $idea;
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->idea = Idea::find($request->idea_id);

        if ($this->idea === null) {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = $this->idea->comments()->paginate(20);
        return response()->json($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCommentRequest $request)
    {
        $comment = new Comment();
        $comment->description = $request->description;

        if ($user = Auth::user()) {
            $comment->user_id = $user->id;
        }

        $this->idea->comments()->save($comment);

        return $comment;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AuthorizedRequest $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorizedRequest $request, $idea_id, Comment $comment)
    {
        // Check admin access
        if (!$request->user()->hasAdminAccess($comment)) {
            return abort(403);
        }

        $comment->delete();
    }
}
