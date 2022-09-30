<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\ShowMessagesRequest;
use App\Http\Requests\Message\ShowMessageRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Requests\Message\DestroyMessageRequest;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Show messages
     * 
     * @param  \App\Http\Requests\Message\ShowMessagesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ShowMessagesRequest $request)
    {
        //
    }

    /**
     * Show message
     * 
     * @param  \App\Http\Requests\Message\ShowMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowMessageRequest $request, $id)
    {
        //
    }

    /**
     * Store new massage
     * 
     * @param  \App\Http\Requests\Message\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        //
    }

    /**
     * Update message
     * 
     * @param  \App\Http\Requests\Message\UpdateMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRequest $request, $id)
    {
        //
    }

    /**
     * Remove message
     * 
     * @param  \App\Http\Requests\Message\DestroyMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyMessageRequest $request, $id)
    {
        //
    }
}
