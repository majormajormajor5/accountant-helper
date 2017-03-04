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
use Illuminate\Support\Facades\Input;

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
            'user_id' => 'not_present',
            'beginning_sum' => 'not_present',
            'balance' => 'not_present',
            'taxes' => 'not_present',
            'created_at' => 'not_present',
            'updated_at' => 'not_present',
            'month' => 'not_present'
        ];

        $dataForValidation = [$request['columnName'] => $request['value']];
        $validator = Validator::make($dataForValidation, $rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ], 200);
        }

        if ($request['columnName'] == 'ending_sum') {
            $month = Month::where('id', $monthId)
                ->where('user_id', Auth::user()->id)
                ->firstOrFail();

            if ($previousMonth = $this->getPreviousMonthFrom($month)) {
                $month->beginning_sum = $month->beginning_sum + $previousMonth->balance;
            }

            $month->ending_sum = $request['value'];
            $month->balance = $month->beginning_sum - $month->ending_sum;

            $month->save();
        } else {
            Month::where('id', $monthId)
                ->where('user_id', Auth::user()->id)
                ->update([
                    $request['columnName'] => $request['value'],
                    'updated_at' => 'now'
                ]);
        }

        return Response::json(['success' => true], 200);
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

    public function byBuilding(Request $request, $buildingId)
    {
        $this->addMonthIfNeeded($buildingId);

        $months = Month
            ::where('months.building_id', $buildingId)
            ->where('month', $this->getPreviousMonthFromCurrent()->format('Y-m-d'));

        if ($request['from-date']) {
            $months = $months->where('month', '>=', $request['from-date']);
        }

        if ($request['to-date']) {
            $months = $months->where('month', '<=', $request['to-date']);
        }

        if ($request['from-apartment']) {
            $months = $months->where('apartment_id', '>=', $request['from-apartment']);
        }

        if ($request['to-apartment']) {
            $months = $months->where('apartment_id', '<=', $request['to-apartment']);
        }

        $months = $months
            ->join('apartments', 'months.apartment_id', '=', 'apartments.id')
            ->get()
            ->sortBy('number'); //Sort by apartment's number
        //TODO figure out why not empty json object causing javascript to crash on this page
        foreach ($months as $month) {
            $month->taxes = '{}';
        }
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

        return view('months.by-building.index', compact('months', 'buildingId', 'building', 'request'));
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


    public function getPreviousMonthFromCurrent()
    {
        //fallback
        if ($this->firstMonth->getTimestamp() === $this->currentMonth->getTimestamp()) {
            return $this->currentMonth;
        }

        return $this->previousMonth;
    }
    
    public function getPreviousMonthFrom($month)
    {
        $previousMonth = Month::where('user_id', Auth::user()->id)
            ->where('apartment_id', $month->apartment_id)
            ->where('month', Carbon::createFromFormat('Y-m-d', $month->month)->sub(new \DateInterval('P1M'))->firstOfMonth())
            ->first();

        if ($previousMonth) {
            return $previousMonth;
        }

        return false;
    }
}
