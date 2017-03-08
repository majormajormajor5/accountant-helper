<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Building;
use App\Bill;
use App\Month;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BillsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function byBuilding($buildingId)
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bills.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byBuildingCreate($buildingId)
    {
        $building = Building::where('user_id', Auth::user()->id)
            ->where('id', $buildingId)
            ->with(['apartments'])
            ->first();

        return view('bills.by-building.create', compact('building'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function byBuildingStore(Request $request, $buildingId)
    {
        $months = Month::where('user_id', Auth::user()->id)
            ->where('building_id', $buildingId)
            ->where('month', Carbon::createFromFormat('m Y', $request['month'])->firstOfMonth()->format('Y-m-d'))
            ->with(['apartment', 'apartment.building', 'apartment.building.organization'])
            ->get();

        $bill = Bill::where('user_id', Auth::user()->id)
            ->first();

        foreach ($months as $month) {
            $taxes = json_decode($month->taxes, true);

            $billData = [];
            $billData[] = '__beginning_sum__';
            $billData[] = '__ending_sum__';
            $billData[] = '__balance__';

            $billDataReplacements = [];
            $billDataReplacements[] = $month->beginning_sum;
            $billDataReplacements[] = $month->ending_sum;
            $billDataReplacements[] = $month->balance;

            foreach ($taxes['variables'] as $name => $value) {
                $billData[] = '__' . $name . '__';
                $billDataReplacements[] = $value;
            }

            $billContent = str_replace($billData, $billDataReplacements, $bill->content);
            var_dump($billContent);die;
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function send()
    {

    }
}
