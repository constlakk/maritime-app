<?php

namespace App\Http\Controllers;

use App\Models\VesselOpex;
use App\Http\Requests\StoreVesselOpexRequest;
use App\Http\Requests\UpdateVesselOpexRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Http\Resources\VesselOpexResource;
use App\Models\Vessel;

class VesselOpexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVesselOpexRequest $request, $id)
    {

        $vessel = Vessel::find($id);

        if ($vessel === null) {

            return response("Entity with id {$id} not found", Response::HTTP_NOT_FOUND);

        }
        
        $acceptable_parameters = ['date' => '', 'expenses' => ''];

        $given_parameters = $request->all();

        foreach ($given_parameters as $key => $value) {

            if(!array_key_exists($key, $acceptable_parameters)) {

                return response("Acceptable parameters are: date, expenses", Response::HTTP_BAD_REQUEST);

            }

        }

        $amounts_check = \DB::table('vessel_opexes')->where('vessel_id', '=', $id)->where('date', '=', $request->get('date'))->get();

        if(count($amounts_check) > 0) {

            return response("A vessel cannot have two different opex amounts for the same date.", Response::HTTP_BAD_REQUEST);

        }

        $vessel_id = ['vessel_id' => $id];

        return new VesselOpexResource(VesselOpex::create(array_merge($request->all(), $vessel_id)));

    }

    /**
     * Display the specified resource.
     */
    public function show(VesselOpex $vesselOpex): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VesselOpex $vesselOpex): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVesselOpexRequest $request, VesselOpex $vesselOpex): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VesselOpex $vesselOpex): RedirectResponse
    {
        //
    }
}
