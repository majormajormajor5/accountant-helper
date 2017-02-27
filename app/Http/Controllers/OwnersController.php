<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class OwnersController extends Controller
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
        $rules = [
            'user_id' => 'not_present'
        ];

        $validator = Validator::make($request->all(), $rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ], 200);
        }

        $owner = new Owner([
            'first_name' => '',
            'second_name' => '',
            'patronymic' => '',
            'email' => '',
            'phone' => '',
            'user_id'=> Auth::user()->id,
            'created_at' => 'now',
            'updated_at' => 'now'
        ]);

        $createdOwner = Apartment::where('id', $request['apartmentId'])
            ->where('user_id', Auth::user()->id)
            ->first()
            ->owners()
            ->save($owner);

        return Response::json([
            'success' => true,
            'ownerId' => $createdOwner->id
        ], 200);
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

        Owner::where('id', $request['ownerId'])
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
        $owner = Owner::where('id', $id)
            ->where('user_id', Auth::user()->id);

        if ($owner) {
            if ($owner->delete()) {
                return response('', 200);
            }

            return response(view('owners.partials.cannot-delete-alert'), 200);
        }

        return response('', 500);
    }

    public function byApartment($apartmentId)
    {


        $apartment = Apartment::findOrFail($apartmentId);
        $owners = $apartment->owners;

        return view('owners.by-apartment.index', compact('apartment', 'owners'));
    }
}
