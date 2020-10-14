<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\UserActivate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        Mail::to($user->email)->send(new UserActivate());

        return response(['data' => $user, 'message' => 'Account enabled']);
    }

    public function deactivate(User $user)
    {
        $user->active = false;

        $user->save();

        return response(['data' => $user, 'message' => 'Account disabled']);
    }

    public function declinedUsers(){
        $users = User::where('active', 0)->get();

        return response(['data' => $users]);
        
    }
    public function delete(User $user)
    {
        $user->delete();
        return response(['message' => 'Account deleted']);
    }

    public function deleteAllUsers()
    {
        User::where('active', 0)->delete();
        return response(['message' => 'Accounts deleted']);
    }

    public function userOrders() {
        $orders = Auth::user()->userOrders->all();

        return response(['data' => $orders]);
    }

    public function userPaymentStatus() {
        $paymentStatus = Auth::user()->paymentStatus->all();

        return response(['data' => $paymentStatus]);
    }
}
