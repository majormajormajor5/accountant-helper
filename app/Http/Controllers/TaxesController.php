<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Building;
use App\Month;
use App\Apartment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

class TaxesController extends Controller
{
    public function __construct()
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
    public function edit($monthId)
    {
        $month = Month::where('id', $monthId)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        $taxes = $month->taxes;

        return view('taxes.edit', compact('month', 'taxes'));
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
        $month = Month::where('id', $monthId)
            ->where('user_id', Auth::user()->id)
            ->update($request->only(['taxes']));

        if (! $month) {
            return redirect()->back();
        } else {
            return redirect()->action('TaxesController@byBuilding', [$request['building-id']]);
        }
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
        return var_dump($buildingId);
    }
}
