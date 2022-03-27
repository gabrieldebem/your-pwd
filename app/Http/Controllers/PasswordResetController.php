<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasswordResetRequest;
use App\Http\Requests\UpdatePasswordResetRequest;
use App\Models\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePasswordResetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePasswordResetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PasswordReset  $passwordReset
     * @return \Illuminate\Http\Response
     */
    public function show(PasswordReset $passwordReset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PasswordReset  $passwordReset
     * @return \Illuminate\Http\Response
     */
    public function edit(PasswordReset $passwordReset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePasswordResetRequest  $request
     * @param  \App\Models\PasswordReset  $passwordReset
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePasswordResetRequest $request, PasswordReset $passwordReset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PasswordReset  $passwordReset
     * @return \Illuminate\Http\Response
     */
    public function destroy(PasswordReset $passwordReset)
    {
        //
    }
}
