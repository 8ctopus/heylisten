<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizedRequest;
use App\Http\Requests\CreateProductRequest;
use App\Product;
use App\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $workspace = Workspace::where('alias', $request->get('workspace'))->firstOrFail();
        return $workspace->products()->get()->load('workspace');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $workspace = Workspace::where('alias', $request->workspace)->firstOrFail();

        return $workspace->products()->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $workspace = Workspace::where('alias', $request->workspace)->firstOrFail();
        $product = $workspace->products()->where(['slug' => $id])->firstOrFail()->load(['workspace']);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(CreateProductRequest $request, Product $product)
    {
        // Check admin access
        if (!$request->user()->hasAdminAccess($product)) {
            return abort(403);
        }

        return tap($product)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthorizedRequest $request, Product $product)
    {
        // Check admin access
        if (!$request->user()->hasAdminAccess($product)) {
            return abort(403);
        }

        $product->delete();
    }
}
