<?php

namespace App\Http\Controllers\Settings;

use App\Validation\Rules\AlphaDashEnglish;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // make workspace alias lower case
        $request->merge(['workspace' => strtolower($request->workspace)]);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'workspace' => [
                'required',
                'min:5',
                'max:250',
                new AlphaDashEnglish,
                Rule::unique('workspaces', 'alias')->ignore($user->workspace->id),
                Rule::notIn(Workspace::RESTRICTED_NAMES)
            ]
        ]);

        $user->workspace->alias = $request->workspace;
        $user->workspace->save();

        return tap($user)->update($request->only('name', 'email'));
    }
}
