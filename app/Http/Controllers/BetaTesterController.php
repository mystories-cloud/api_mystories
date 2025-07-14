<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Imports\BetaTestersImport;
use App\Models\BetaTester;
use App\Services\Uploads\CSVUploader;
use App\Traits\ApiResponse\Response;
use Exception;
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
        try {

            $betaTester->forceDelete();

            return response()->json(['message' => 'Your data was deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function import(ImportRequest $request, CSVUploader $uploader)
    {
        try {

            $uploader->import(new BetaTestersImport);

            $data = BetaTester::orderByDesc('id')->get();

            return $this->response($data);
        } catch (Exception $e) {
            return $this->response(null, $e);
        }
    }
}
