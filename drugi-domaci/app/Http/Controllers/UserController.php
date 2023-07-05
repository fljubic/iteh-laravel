<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithItsToDoListsResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->sortBy('id');
        return response()->json(['users' => UserResource::collection($users)]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $user = User::find($user_id);
        if (is_null($user)) {
            return response()->json(['message' => 'user not found'], 404);
        }
        return response()->json(['user' => new UserWithItsToDoListsResource($user)], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        if (auth()->user()->id != $user_id) {
            return response()->json(['message' => 'you can only delete your accoutn'], 403);
        }
        $user = auth()->user();
        auth()->user()->tokens()->delete(); // logging out
        $user->delete(); //deleting user
        return response()->json(['message' => 'user successfully deleted'], 200);
    }
}
