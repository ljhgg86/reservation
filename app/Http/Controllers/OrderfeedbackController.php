<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Orderfeedback;

class OrderfeedbackController extends Controller
{
    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->orderfeedback=new Orderfeedback();
    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orderfeedback = new Orderfeedback();
        $orderfeedback->user_id = request()->user()->id;
        $orderfeedback->info_id = $request->input('info_id');
        $orderfeedback->feedbackContent = $request->input('feedbackContent');

        return response()->responseUtil($orderfeedback->save());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orderfeedback $orderfeedback)
    {
        return response()->responseUtil($orderfeedback->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orderfeedback $orderfeedback)
    {
        return response()->responseUtil($orderfeedback->delete());
    }

    public function infoFeedbacks($info_id){
        return response()->responseUtil($this->orderfeedback->infoFeedbacks($info_id));
    }
}