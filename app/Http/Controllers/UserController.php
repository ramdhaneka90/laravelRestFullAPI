<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Transformers\UserTransformer;

class UserController extends Controller
{
    public function getUserAPI(User $user)
    {
        $users = $user->all();

        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

    public function getUserProfileAPI(User $user)
    {
        $user = $user->find(Auth::user()->id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->includePosts()
            ->toArray();
    }

    public function getUserProfileByIdAPI(User $user, $id)
    {
        $user = $user->find($id);

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->includePosts()
            ->toArray();
    }

    public function updateUserProfileAPI(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|unique:users',
            'password' => 'min:6',
        ]);

        $user = User::find(Auth::user()->id);

        if(Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Your password don\'t matches'], 401);
        }

        $user->name = $request->get('name', $user->name);
        $user->email = $request->get('email', $user->email);

        if( isset($request->password) ) {
            $user->password = bcrypt($request->password);
        } else {
            $user->password = $request->get('password', $user->password);
        }

        $user->save();

        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer)
            ->toArray();
    }

    public function destroyUserProfileAPI()
    {
        $user = User::find(Auth::user()->id);
        $user->delete();

        return response()->json([
            'message' => 'Delete User Successfully!'
        ]);
    }
}
