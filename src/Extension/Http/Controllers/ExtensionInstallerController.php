<?php

namespace Core\Extension\Http\Controllers;

use App\Http\Controllers\Controller;
use Core\Extension\Repository\ExtensionRepository;
use Illuminate\Http\Request;

class ExtensionInstallerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExtensionRepository $extension)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('extension::installer');
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
     * @param  \Core\Extension\Extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function show(ExtensionRepository $extension)
    { 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Core\Extension\Extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtensionRepository $extension)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Core\Extension\Extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionRepository $extension)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Core\Extension\Extension  $extension
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionRepository $extension)
    {
        //
    }
}
