<?php

namespace App\Http\Controllers;

use App\Building;
use App\Month;
use App\Apartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthsController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function byBuilding($buildingId)
    {
        $this->addMonthIfNeeded($buildingId);

        $months = Building::where('id', $buildingId)
            ->where('user_id', Auth::user()->id)
            ->first()
            ->months()
            ->get();

        return view('months.by-building.index', compact('months'));
    }

    public function addMonthIfNeeded($buildingId)
    {
        $latestMonth = \DateTimeImmutable::createFromFormat(
            'Y-m-d',
            Month::where('building_id', $buildingId)->max('month')
        );

        $currentMonth = (new \DateTimeImmutable())->modify('first day of this month');
        $nextMonth = $currentMonth->add(new \DateInterval('P1M'));

        if ($latestMonth->getTimestamp() < $nextMonth->getTimestamp()) {
            $apartments = Apartment::where('building_id', $buildingId)->pluck('id')->toArray();

            $months = [];
            foreach ($apartments as $apartment) {
                $month = [];
                $month['month'] = $nextMonth->format('Y-m-d');
                $month['beginning_sum'] = 0;
                $month['ending_sum'] = 0;
                $month['balance'] = 0;
                $month['taxes'] = '{}';
                $month['created_at'] = 'now';
                $month['updated_at'] = 'now';
                $month['apartment_id'] = $apartment;
                $month['building_id'] = $buildingId;
                $months[] = $month;
            }

            if(Month::insert($months)) {
                return true;
            }
        }

        return false;
    }
}
