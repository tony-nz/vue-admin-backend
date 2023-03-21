<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //   $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return User::orderBy('name')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUser $request
     * @return UserResource
     */
    public function store(StoreUser $request)
    {
        $user = User::make($request->all());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUser $request
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->update($request->except('password'));
        if ($password = $request->input('password')) {
            $user->password = Hash::make($password);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}