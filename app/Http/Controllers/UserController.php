<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(UserResource::collection(User::paginate(10)), 'Users retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUser $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = User::make($request->all());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return $this->sendResponse(new UserResource($user), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUser  $user
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $user)
    {
        $user->update($request->except('password'));
        if ($password = $request->input('password')) {
            $user->password = Hash::make($password);
        }

        return $this->sendResponse(new UserResource($user), 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->sendResponse([], 'User deleted successfully.');
    }
}