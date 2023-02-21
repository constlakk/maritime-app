<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use App\Models\Voyage;
use App\Http\Requests\StoreVesselRequest;
use App\Http\Requests\UpdateVesselRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Http\Resources\VesselResource;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Vessel::all();

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
    public function store(StoreVesselRequest $request)
    {
        
        return new VesselResource(Vessel::create($request->all()));

    }

    /**
     * Display the specified resource.
     */
    public function show(Vessel $vessel): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vessel $vessel): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVesselRequest $request, $id)
    {
        
        $vessel = Vessel::find($id);

        //If name exists in request, update all relevant voyages codes
        if($request->get('name') !== null) {

            $voyages = \DB::table('voyages')->where('vessel_id', $id)->get();

            \DB::transaction(function () use ($voyages, $request, $id) {

                foreach($voyages as $voyage) {

                    $exploded_array = explode("-", $voyage->code);

                    $exploded_array[0] = $request->get('name');

                    $imploded_array = implode("-", $exploded_array);

                    print_r($imploded_array);                 

                    Voyage::where('vessel_id', $id)->update(['code' => $imploded_array]);

                }

            });

        }

        $vessel->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vessel $vessel): RedirectResponse
    {
        //
    }
}
