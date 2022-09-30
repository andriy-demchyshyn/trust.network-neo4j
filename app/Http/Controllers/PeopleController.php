<?php

namespace App\Http\Controllers;

use App\Http\Requests\People\ShowPeopleRequest;
use App\Http\Requests\People\ShowPersonRequest;
use App\Http\Requests\People\StorePersonRequest;
use App\Http\Requests\People\UpdatePersonRequest;
use App\Http\Requests\People\DestroyPersonRequest;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    /**
     * Show people
     * 
     * @param  \App\Http\Requests\People\ShowPeopleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ShowPeopleRequest $request)
    {
        //
    }

    /**
     * Show person
     * 
     * @param  \App\Http\Requests\People\ShowPersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowPersonRequest $request, $id)
    {
        //
    }

    /**
     * Store new person
     * 
     * @param  \App\Http\Requests\People\StorePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonRequest $request)
    {
        //
    }

    /**
     * Update person
     * 
     * @param  \App\Http\Requests\People\UpdatePersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        //
    }

    /**
     * Remove person
     * 
     * @param  \App\Http\Requests\People\DestroyPersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyPersonRequest $request, $id)
    {
        //
    }
}
