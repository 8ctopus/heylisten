<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CreateIdeaRequest;
use App\Http\Requests\AuthorizedRequest;
use App\Idea;
use App\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get order
        switch ($request->order) {
            case 'top':
                $orderRaw = 'CASE WHEN category_id = ' . Category::PINNED . ' THEN -1 ELSE 0 END, votes DESC';
                break;

            case 'hot':
                // not implemented
                abort(404);
                break;

            case 'new':
                $orderRaw = 'created_at DESC';
                break;

            default:
                abort(404);
                break;
        }

        // get workspace
        $workspace = Workspace::where('alias', $request->workspace)->firstOrFail();

        // get product
        $product = $workspace->products()->where('slug', $request->product)->firstOrFail();

        $user_id = $product->workspace->user_id;

        $ideas = $product->ideas()
            ->orderByRaw($orderRaw)
            ->withCount('comments')
            ->with([
                'comments' => function($query) use($user_id){
                    $query->where('user_id', '=', $user_id)->orderByDesc('id');
                }
            ]);

        $ideas = $ideas->paginate(20);

        return response()->json($ideas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateIdeaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateIdeaRequest $request)
    {
        $product = $request->_product;

        $idea = $product->ideas()->create($request->all());

        if ($user = Auth::user()) {
            $idea->user_id = $user->id;
            $idea->save();
        }

        $idea->sendCreateNotification();

        return $idea;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Idea::withCount('comments')->with(['category'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        // Check admin access
        if (!$request->user()->hasAdminAccess($idea)) {
            return abort(403);
        }

        $idea->update($request->all());

        $idea = $this->getIdeaWithAdminComments($idea->id);

        return $idea;
    }

    public function vote(Request $request, Idea $idea) {
        // Update votes count
        if (isset($request->vote)) {
            if ($request->vote === 'add') $idea->votes++;
            else if ($request->vote === 'remove') $idea->votes--;

            $idea->save();
        }

        $idea = $this->getIdeaWithAdminComments($idea->id);

        return $idea;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AuthorizedRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorizedRequest $request, Idea $idea)
    {
        // Check admin access
        if (!$request->user()->hasAdminAccess($idea)) {
            return abort(403);
        }

        $idea->delete();
    }

    /**
     * Get idea from $id with admin comments and comments count
     *
     * @param  int  $id
     * @return Idea
     */
    protected function getIdeaWithAdminComments($id) {
        $admin_id = $this->getWorkspace(Idea::find($id))->user_id;
        return Idea::withCount('comments')
            ->with([
                'comments' => function($query) use ($admin_id) {
                    $query->where('user_id', '=', $admin_id)->orderByDesc('id');
                }
            ])
            ->findOrFail($id);
    }

    protected function getWorkspace(Idea $idea) {
        return $idea->product->workspace;
    }
}
