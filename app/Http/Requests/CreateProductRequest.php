<?php

namespace App\Http\Requests;

use App\Validation\Rules\AlphaDashEnglish;
use App\Workspace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateProductRequest extends AuthorizedRequest
{
    protected $workspace;

    public function authorize()
    {
        $this->workspace = Workspace::where('alias', $this->request->get('workspace'))->firstOrFail();

        if (Auth::id() !== $this->workspace->user->id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $workspace = $this->workspace;
        $product = $this->route()->parameter('product');

        $id = $product ? $product->id : null;

        // Slug lowercase
        request()->merge(['slug' => strtolower(request()->slug)]);

        return [
            'name' => ['required', 'max:255'],
            'slug' => [
                'required',
                'max:255',
                new AlphaDashEnglish,
                Rule::unique('products')->where(function ($query) use($workspace) {
                    return $query->where('workspace_id', $workspace->id)->where('deleted_at', null);
                })->ignore($id),
            ]

        ];
    }
}
