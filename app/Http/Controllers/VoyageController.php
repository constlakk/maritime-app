<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use App\Http\Requests\StoreVoyageRequest;
use App\Http\Requests\UpdateVoyageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Http\Resources\VoyageResource;
use App\Models\Vessel;

class VoyageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return Voyage::all();

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
    public function store(StoreVoyageRequest $request)
    {

        $acceptable_parameters = ['vessel_id' => '', 'start' => '', 'end' => '', 'revenues' => '', 'expenses' => ''];

        $given_parameters = $request->all();

        foreach ($given_parameters as $key => $value) {

            if(!array_key_exists($key, $acceptable_parameters)) {

                return response("Acceptable parameters are: vessel_id, start, end, revenues, expenses.", Response::HTTP_BAD_REQUEST);

            }

        }
        
        $vessel = Vessel::find($request->get('vessel_id'));

        if ($vessel === null) {

            return response("Entity with id {$vessel->id} not found", Response::HTTP_NOT_FOUND);

        }

        $code = ['code' => $vessel->name . "-" . $request->get('start')];
        $status = ['status' => 'pending'];

        return new VoyageResource(Voyage::create(array_merge($request->all(), $code, $status)));

    }

    /**
     * Display the specified resource.
     */
    public function show(Voyage $voyage): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voyage $voyage): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoyageRequest $request, $id)
    {

        $acceptable_parameters = ['start' => '', 'end' => '', 'revenues' => '', 'expenses' => '', 'status' => ''];

        $given_parameters = $request->all();

        foreach ($given_parameters as $key => $value) {

            if(!array_key_exists($key, $acceptable_parameters)) {

                return response("Acceptable parameters are: start, end, revenues, expenses, status.", Response::HTTP_BAD_REQUEST);

            }

        }
        
        $voyage = Voyage::find($id);

        if($voyage['status'] === 'submitted') {

            return response("Submitted voyages cannot be edited.", Response::HTTP_BAD_REQUEST);

        }

        if($voyage === null) {

            return response("Entity with id {$id} not found", Response::HTTP_NOT_FOUND);

        }

        if($request->get('status') !== null) {

            if(!in_array($request->get('status'), ['pending', 'ongoing', 'submitted'])) {

                return response("Acceptable status values: pending, ongoing, submitted.", Response::HTTP_BAD_REQUEST);

            }

        }

        if($request->get('status') === 'ongoing') {

            //$vessel_voyages = Voyage::where('vessel_id', $voyage['vessel_id']);
            $vessel_voyages = \DB::table('voyages')->where('vessel_id', '=', $voyage['vessel_id'])->where('status', '=', 'ongoing')->get();
            
            if(count($vessel_voyages) > 0) {

                return response("Vessel cannot have more than one ongoing voyages.", Response::HTTP_BAD_REQUEST);

            }

        }

        //update voyage code if start date is changed
        if($request->get('start') !== null)  {

            $split_code = explode("-", $voyage['code']);

            $code = ['code' => $split_code[0] . "-" . $request->get('start')];

        }

        //submitted status checks
        if($request->get('status') === 'submitted') {

            if(
                ($voyage['start'] !== null || $request->get('start') !== null) &&
                ($voyage['end'] !== null || $request->get('end') !== null) &&
                ($voyage['revenues'] !== null || $request->get('revenues') !== null) &&
                ($voyage['expenses'] !== null || $request->get('expenses') !== null)
            ){

                if($request->get('revenues') !== null) {

                    $voyage['revenues'] = $request->get('revenues');

                }

                if($request->get('expenses') !== null) {

                    $voyage['expenses'] = $request->get('expenses');

                }

                $profit = ['profit' => $voyage['revenues'] - $voyage['expenses']];

            } else {

                return response("Parameters start, end, revenues and expenses must not be null for a voyage to be submitted.", Response::HTTP_BAD_REQUEST);

            }

        }

        $merged_parameters = array_merge($request->all());

        if(isset($code)) {

            $merged_parameters = array_merge($request->all(), $code);

        }

        if(isset($profit)) {

            $merged_parameters = array_merge($request->all(), $profit);

        }

        if ($voyage->update(($merged_parameters)) === false) {

            return response("Couldn't update entity with id {$id}", Response::HTTP_BAD_REQUEST);

        }

        return response($voyage);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voyage $voyage): RedirectResponse
    {
        //
    }
}
