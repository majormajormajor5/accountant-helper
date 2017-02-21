<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreApartmentRequest;

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
                $apartment['created_at'] = 'now';
                $apartment['updated_at'] = 'now';

                $apartments[] = $apartment;
            }

            Apartment::insert($apartments);

        } else {
            $apartment = [];
            $apartment['number'] = $data['number'];
            $apartment['square'] = 0.0000;
            $apartment['number_of_residents'] = 1;
            $apartment['building_id'] = $buildingId;

            Apartment::create($apartment);
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

    public function makeBulk()
    {
        $res = [];

        function make(&$result) {
            $bulkData = [];
            $counter = 1;
            $apartments = [];
            for ($i = 1; $i < 5; $i++) {
                $apartment = [];
                $apartment['number'] = $i;
                $apartment['square'] = 0.0000;
                $apartment['number_of_residents'] = 1;
                $apartment['building_id'] = 12;

//            $bulkData[] = $apartment;

                $result = $result + array_merge($bulkData, $apartment);
            }

            var_dump($result);
        }
        make($res);
        var_dump($res);
        die;
    }
}
