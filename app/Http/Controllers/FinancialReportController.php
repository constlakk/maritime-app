<?php

namespace App\Http\Controllers;

use DateTime;
use App\Http\Requests\StoreFinancialReportRequest;
use App\Http\Requests\UpdateFinancialReportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Http\Resources\FinancialReportResource;
use App\Models\Vessel;
use App\Models\VesselOpex;
use App\Models\Voyage;

class FinancialReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        
        $voyages = \DB::table('voyages')
        ->where('vessel_id', $id)
        ->select('id AS voyage_id', 'start', 'end', 'revenues AS voyage_revenues', 'expenses AS voyage_expenses', 'profit AS voyage_profit')
        ->get();

        foreach($voyages as $voyage) {

            //daily average profit
            $start = new DateTime($voyage->start);
            $end = new DateTime($voyage->end);

            $date_diff = $end->diff($start)->format("%a");

            $voyage_profit_daily_average = $voyage->voyage_profit / $date_diff;

            $voyage->voyage_profit_daily_average = $voyage_profit_daily_average;


            //total vessel expenses & net profit
            $vessel_days_collection = \DB::table('vessel_opexes')->where('vessel_id', $id)->whereBetween('date', [$start, $end])->get();
            $vessel_expenses_total = $vessel_days_collection->sum('expenses');

            $voyage->vessel_expenses_total = $vessel_expenses_total;

            $voyage->net_profit = $voyage->voyage_profit - $vessel_expenses_total;


            //daily average net profit
            $voyage->net_profit_daily_average = $voyage->net_profit / $date_diff;

        }

        return $voyages;

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
