<?php

namespace App\Http\Requests;

use App\Models\Workspace;
use App\Validation\Rules\RichText;
use Illuminate\Foundation\Http\FormRequest;

class CreateIdeaRequest extends FormRequest
{
    /**
     * Transform the error messages into JSON
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $errors)
    {
        return response()->json($errors, 422);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $workspace = Workspace::where('alias', $this->request->get('workspace'))->firstOrFail();
        $product = $workspace->products()->where('slug', $this->request->get('product'))->firstOrFail();
        $this->_product = $product;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // clean html
        request()->merge(['description' => RichText::cleanEmptyLines(request()->description)]);

        return [
            'title' => ['required', 'between:3,200'],
            'email' => ['nullable', 'email'],
            'description' => [new RichText],
            'image' => 'sometimes|base64dimensions:max_width=1920,max_height=1920|base64mimes:jpeg,png,gif|base64max:1024',
        ];
    }
}
