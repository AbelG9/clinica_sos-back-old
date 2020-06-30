<?php

namespace App\Http\Controllers;

use App\Model\Pes_tool;
use Illuminate\Http\Request;

class Pes_tollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $show = Pes_tool::paginate(10);
        return $show;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Pes_tool  $pes_tool
     * @return \Illuminate\Http\Response
     */
    public function show(Pes_tool $pes_tool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Pes_tool  $pes_tool
     * @return \Illuminate\Http\Response
     */
    public function edit(Pes_tool $pes_tool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Pes_tool  $pes_tool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pes_tool $pes_tool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Pes_tool  $pes_tool
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pes_tool $pes_tool)
    {
        //
    }
}
