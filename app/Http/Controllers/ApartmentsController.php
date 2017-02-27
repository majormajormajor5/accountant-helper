<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Building;
use App\Http\Requests\UpdateApartmentRequest;
use App\Month;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreApartmentRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ApartmentsController extends Controller
{
    public $bulk = [];

    public function __construct()
    {
        $this->middleware('auth');
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
    public function create($buildingId)
    {
        $buildings = Building::where('user_id', Auth::user()->id)->get()->pluck('name', 'id');

        return view('apartments.create', compact('buildings', 'buildingId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApartmentRequest $request, $buildingId)
    {
        $data = $request->all();

        if (isset($data['fromNumber'])) {
            $data['toNumber'] = abs($data['fromNumber']) + abs($data['quantity']);

            $apartments = [];
            for ($i = (int) $data['fromNumber']; $i < $data['toNumber']; $i++) {
                $apartment = [];
                $apartment['number'] = $i;
                $apartment['square'] = 0.0000;
                $apartment['number_of_residents'] = 1;
                $apartment['building_id'] = $buildingId;
                $apartment['user_id'] = Auth::user()->id;
                $apartment['owners_email'] = '';
                $apartment['created_at'] = 'now';
                $apartment['updated_at'] = 'now';

                $apartments[] = $apartment;
            }

            Apartment::insert($apartments);

            $apartments = Apartment::where('building_id', $buildingId)->pluck('id')->toArray();

            $months = [];
            foreach ($apartments as $apartment) {
                $month = [];
                $month['month'] = Carbon::now()->startOfMonth()->format('Y-m-d');
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

            Month::insert($months);

        } else {
            $apartment = [];
            $apartment['number'] = $data['number'];
            $apartment['square'] = 0.0000;
            $apartment['number_of_residents'] = 1;
            $apartment['building_id'] = $buildingId;
            $apartment['user_id'] = Auth::user()->id;
            $apartment['owners_email'] = '';

            $apartment = Apartment::create($apartment);

            if ($apartment) {
                $month = [];
                $month['month'] = Carbon::now()->startOfMonth()->format('Y-m-d');
                $month['beginning_sum'] = 0;
                $month['ending_sum'] = 0;
                $month['balance'] = 0;
                $month['taxes'] = '{}';
                $month['created_at'] = 'now';
                $month['updated_at'] = 'now';
                $month['apartment_id'] = $apartment->id;
                $month['building_id'] = $buildingId;

                Month::create($month);
            }
        }

        return redirect('buildings/' . $buildingId . '/apartments');
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
    public function update(Request $request)
    {
        $rules = [
            'user_id' => 'not_present',
            'number_of_residents' => 'integer',
            'square' => ['regex:/^[0-9]{1,6}\.[0-9]{0,4}$/']
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

        Apartment::where('id', $request['apartmentId'])
            ->where('user_id', Auth::user()->id)
            ->update([$request['columnName'] => $request['value']]);

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
        $apartment = Apartment::where('id', $id)
            ->where('user_id', Auth::user()->id);

        if ($apartment) {
            if ($apartment->delete()) {
                return response('', 200);
            }

            return response(view('apartments.partials.cannot-delete-alert'), 200);
        }

        return response('', 500);
    }
}
