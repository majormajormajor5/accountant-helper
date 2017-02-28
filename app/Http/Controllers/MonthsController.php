<?php

namespace App\Http\Controllers;

use App\Building;
use App\Month;
use App\Apartment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class MonthsController extends Controller
{
    public $currentMonth;
    public $previousMonth;
    public $nextMonth;
    public $lastMonth;
    public $firstMonth;

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
    public function update(Request $request, $monthId)
    {
        $rules = [
            'user_id' => 'not_present'
        ];

        $dataForValidation = [$request['columnName'] => $request['value']];
        $validator = Validator::make($dataForValidation, $rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 200);
        }

        Month::where('id', $monthId)
            ->where('user_id', Auth::user()->id)
            ->update([
                $request['columnName'] => $request['value'],
                'updated_at' => 'now'
            ]);

        return Response::json(array('success' => true), 200);
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

        $months = Month
            ::where('months.building_id', $buildingId)
            ->where('month', $this->getPreviousMonth()->format('Y-m-d'))
            ->join('apartments', 'months.apartment_id', '=', 'apartments.id')
            ->get()
            ->sortBy('number'); //Sort by apartment's number

//        По сути тоже самое, но 2 запроса вместо одного + трудности с orderBy
//        $months = Building::where('id', $buildingId)
//            ->where('user_id', Auth::user()->id)
//            ->first()
//            ->months()
//            ->with(['apartment', 'building'])
//            ->get();

        $building = Building::where('id', $buildingId)
            ->with(['organization'])
            ->first();

        return view('months.by-building.index', compact('months', 'buildingId', 'building'));
    }

    public function addMonthIfNeeded($buildingId)
    {
        $months = Month::where('building_id', $buildingId)
        ->orderBy('month')
        ->get();

        $this->lastMonth = Carbon::createFromFormat('Y-m-d', $months->last()->month)->firstOfMonth();
        $this->firstMonth = Carbon::createFromFormat('Y-m-d', $months->first()->month)->firstOfMonth();

        $this->currentMonth = Carbon::now()->firstOfMonth();
        $this->previousMonth =  Carbon::now()->sub(new \DateInterval('P1M'))->firstOfMonth();
        $this->nextMonth = Carbon::now()->firstOfMonth()->add(new \DateInterval('P1M'));

        if ($this->lastMonth->getTimestamp() < $this->nextMonth->getTimestamp()) {
            $apartments = Apartment::where('building_id', $buildingId)->pluck('id')->toArray();

            $months = [];
            foreach ($apartments as $apartment) {
                $month = [];
                $month['month'] = $this->nextMonth->format('Y-m-d');
                $month['beginning_sum'] = 0;
                $month['ending_sum'] = 0;
                $month['balance'] = 0;
                $month['taxes'] = '{}';
                $month['user_id'] = Auth::user()->id;
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


    public function getPreviousMonth()
    {
        //fallback
        if ($this->firstMonth->getTimestamp() === $this->currentMonth->getTimestamp()) {
            return $this->currentMonth;
        }

        return $this->previousMonth;
    }
}
