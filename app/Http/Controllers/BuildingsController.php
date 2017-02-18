<?php

namespace App\Http\Controllers;

use App\Building;
use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BuildingsController extends Controller
{
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
        $buildings = Auth::user()->buildings()->get();

        return view('buildings.index', compact('buildings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizations = Auth::user()->organizations()->get()->pluck('name', 'id');

        return view('buildings.create', compact('organizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBuildingRequest $request)
    {
        $request['user_id'] = Auth::user()->id;
        Building::create($request->all());

        return redirect('buildings');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $building = Building::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('buildings.show', compact('building'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $building = Building::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        $organizations = Auth::user()->organizations()->get()->pluck('name', 'id');

        return view('buildings.edit', compact('building', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuildingRequest $request, $id)
    {
        if (Building::where('id', $id)->update($request->except(['_token', '_method']))) {
            return redirect('buildings');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Building::destroy($id)) {
            return response('', 200);
        }

        return response(view('buildings.partials.cannot-delete-alert'), 200);
    }
}
