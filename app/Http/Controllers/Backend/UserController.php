<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
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
        $users = User::all()->load('media')->map(function ($user) {
            $uploads = $user->getMedia('upload_files');

            if (!$uploads->isEmpty()) {

                $user->files = $uploads->map(function($file) {
                    return [
                        'name' => $file->file_name,
                        'path' => $file->getFullUrl()
                    ];
                });
            }

            return $user;
        })->toArray();

        return response(['data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function activate(User $user)
    {
        $user->active = true;

        $user->save();

        return response(['data' => $user, 'message' => 'Account enabled']);
    }

    public function deactivate(User $user)
    {
        $user->active = false;

        $user->save();

        return response(['data' => $user, 'message' => 'Account disabled']);
    }
}
