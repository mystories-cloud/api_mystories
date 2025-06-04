<?php

namespace App\Http\Controllers;

use App\Models\BetaTester;
use App\Traits\ApiResponse\Response;
use Illuminate\Http\Request;

class BetaTesterController extends Controller
{
    use Response;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BetaTester::orderByDesc('id')->get();
        
        return $this->response($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BetaTester $betaTester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BetaTester $betaTester)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BetaTester $betaTester)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BetaTester $betaTester)
    {
        //
    }
}
