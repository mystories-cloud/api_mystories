<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Imports\BetaAppointmentImport;
use App\Models\BetaAppointment;
use App\Services\Uploads\CSVUploader;
use App\Traits\ApiResponse\Response;
use Exception;
use Illuminate\Http\Request;

class BetaAppointmentController extends Controller
{
    use Response;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->response(BetaAppointment::orderByDesc('id'));
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
    public function show(BetaAppointment $betaAppointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BetaAppointment $betaAppointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BetaAppointment $betaAppointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BetaAppointment $betaAppointment)
    {
        try {

            $betaAppointment->forceDelete();

            return response()->json(['message' => 'Your data was deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function import(ImportRequest $request, CSVUploader $uploader)
    {
        try {

            $uploader->import(new BetaAppointmentImport);

            $data = BetaAppointment::orderByDesc('id')->get();

            return $this->response($data);
        } catch (Exception $e) {
            return $this->response(null, $e);
        }
    }
}
