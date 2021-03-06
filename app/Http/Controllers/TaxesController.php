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
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Mail;

class TaxesController extends Controller
{
    public $currentMonth;
    public $previousMonth;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->currentMonth = Carbon::now()->firstOfMonth();
        $this->previousMonth =  Carbon::now()->sub(new \DateInterval('P1M'))->firstOfMonth();
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
        $taxes = json_decode(preg_replace('/(::)|[\$>]/', '', $request['taxes']), true);

        $vars = [];
        $varsReplacementsValues = [];
        $varsReplacements = [];
        foreach ($taxes['variables'] as $variableName => $variableValue) {
            if (! is_numeric($variableValue)) {
//                return redirect()->back();
            }

            $vars[] = $variableName;
            $varsReplacementsValues[] = $taxes["variables"][$variableName];
            $varsReplacements[] = '$taxes["variables"]' . "[\"$variableName\"]";
        }

        //Replace comma delimiters in big numbers like so 233,567 => 233567
        $formula = str_replace(',', '', implode('', $taxes['formula']));
        $formulaWithVariablesValues = str_replace($vars, $varsReplacementsValues, $formula);

//        if (! $this->validateFormula($formulaWithVariablesValues)) {
//            return redirect()->back();
//        }

        $months = Month::where('user_id', Auth::user()->id)
            ->where('building_id', $request['building-id']);

        $filter = false;

        if ($request['from-date']) {
            $months = $months->where('month', '>=', $request['from-date']);
            $filter = true;
        }

        if ($request['to-date']) {
            $months = $months->where('month', '<=', $request['to-date']);
            $filter = true;
        }

        if ($request['from-apartment'] && !$request['to-apartment']) {
            $apartments = Apartment::where('user_id', Auth::user()->id)
                ->get()
                ->pluck('number', 'id');
            $apartmentIds = [];

            foreach ($apartments as $apartmentId => $apartmentNumber) {
                if ($apartmentNumber >= $request['from-apartment']) {
                    var_dump($apartmentNumber);
                    $apartmentIds[] = $apartmentId;
                }
            }
            $months = $months->whereIn('apartment_id', $apartmentIds);
            $filter = true;
        }

        if ($request['to-apartment'] && !$request['from-apartment']) {
            $apartments = Apartment::where('user_id', Auth::user()->id)
                ->get()
                ->pluck('number', 'id');
            $apartmentIds = [];

            foreach ($apartments as $apartmentId => $apartmentNumber) {
                if ($apartmentNumber <= $request['to-apartment']) {
                    $apartmentIds[] = $apartmentId;
                }
            }
            $months = $months->whereIn('apartment_id', $apartmentIds);
            $filter = true;
        }

        if ($request['from-apartment'] && $request['to-apartment']) {
            $apartments = Apartment::where('user_id', Auth::user()->id)
                ->get()
                ->pluck('number', 'id');
            $apartmentIds = [];

            foreach ($apartments as $apartmentId => $apartmentNumber) {
                if ($apartmentNumber >= $request['from-apartment'] && $apartmentNumber <= $request['to-apartment']) {
                    $apartmentIds[] = $apartmentId;
                }
            }

            $months = $months->whereIn('apartment_id', $apartmentIds);
            $filter = true;
        }

        if (! $filter) {
            $months = $months->where('id', $monthId);
        }

//        $months->update($request->only(['taxes']));

        $months = $months->get();

        foreach ($months as $month) {
            //TODO Replace single updates in loop with mass update
            $month->update($request->only(['taxes']));
            $this->recalculateBeginningSum($month);
        }

        if (! $months) {
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

    public function byBuilding(Request $request, $buildingId)
    {
        $months = Month
            ::where('user_id', Auth::user()->id)
            ->where('building_id', $buildingId)
            ->with(['apartment', 'apartment.building'])
            ->where('month', $this->getCurrentMonth()->format('Y-m-d'));

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
            ->get();

        $variables = [];
        foreach ($months as $month) {
            $tax = json_decode($month->taxes, true);
            if (isset($tax['variables'])) {
                foreach ($tax['variables'] as $variableName => $variableValue) {
                    if (! in_array($variableName, $variables)) {
                        $variables[] = $variableName;
                    }
                    $month->taxesVariables = new \StdClass();
                    $month->taxesVariables->{"$variableName"} = $variableValue;
                }
            }
            $month->taxes = '{}';
        }

        $combMonths = [];
        foreach ($months as $month) {
            $combMonths[$month->apartment->number] = $month;
        }

        ksort($combMonths);

        $building = Building::where('id', $buildingId)
            ->with(['organization'])
            ->first();

        return view('taxes.by-building.index', compact('months', 'buildingId', 'building', 'request', 'variables', 'combMonths'));
    }

    public function getCurrentMonth()
    {
        return $this->currentMonth;
    }

    public function updateTaxesVariables(Request $request, $monthId)
    {
        $request['variableName'] = json_decode('"'.$request['variableName'].'"');

        $rules = [
            'user_id' => 'not_present',
            'beginning_sum' => 'not_present',
            'balance' => 'not_present',
            'taxes' => 'not_present',
            'created_at' => 'not_present',
            'updated_at' => 'not_present',
            'month' => 'not_present',
//            'value' => 'numeric'
            'value' => ['regex:/^[0-9]{1,16}\.[0-9]{0,4}$/']
        ];

        $dataForValidation = [$request['variableName'] => $request['value']];
        $validator = Validator::make($dataForValidation, $rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ], 200);
        }


        $month = Month::where('id', $monthId)
            ->where('user_id', Auth::user()->id)
            ->first();

        $taxes = json_decode($month->taxes, true);

        $taxes['variables'][$request['variableName']] = $request['value'];
//        echo $ad;
        $month->update(['taxes' => json_encode($taxes, JSON_UNESCAPED_UNICODE)]);

        $this->recalculateBeginningSum($month);

        return Response::json(['success' => true], 200);
    }

    public function recalculateBeginningSum($month)
    {
        $taxes = json_decode(preg_replace('/(::)|[\$>]/', '', $month->taxes), true);


        $vars = [];
        $varsReplacementsValues = [];
        $varsReplacements = [];
        foreach ($taxes['variables'] as $variableName => $variableValue) {
            $vars[] = $variableName;
            $varsReplacementsValues[] = $taxes["variables"][$variableName];
            $varsReplacements[] = '$taxes["variables"]' . "[\"$variableName\"]";
        }

        //Replace comma delimiters in big numbers like so 233,567 => 233567
        $formula = str_replace(',', '', implode('', $taxes['formula']));
        $formulaWithVariablesValues = str_replace($vars, $varsReplacementsValues, $formula);
        $formulaWithVariables = str_replace($vars, $varsReplacements, $formula);

//        if (! $this->validateFormula($formulaWithVariablesValues)) {
//            return false;
//        }

        $formulaWithVariables = str_replace('month_square', $month->apartment->square, $formulaWithVariables);
        $formulaWithVariables = str_replace('month_number_of_residents', $month->apartment->number_of_residents, $formulaWithVariables);
        var_dump($formulaWithVariables);
        var_dump($month->square);
        eval( '$beginningSum = (' . $formulaWithVariables . ');' );

        var_dump($formulaWithVariablesValues);
        $month->beginning_sum = $beginningSum;
        if ($previousMonth = $this->getPreviousMonthFrom($month)) {
            $month->beginning_sum += $previousMonth->balance;
        }
        $month->balance = $month->beginning_sum - $month->ending_sum;
        $month->save();

        return $month->beginning_sum;
    }

    public function validateFormula($formula)
    {
        return preg_match('/^([-+]? ?([0-9]+([.][0-9]+)?|\(\g<1>\))( ?[-+*\/] ?\g<1>)?)$/', trim($formula)) > 0;
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

    public function updateTaxesVariablesTest(Request $request)
    {
        $monthId = 46;
        $request['variableName'] = 'счетчик';
        $request['variableName'] = json_decode('"'.$request['variableName'].'"');
        $request['value'] = 666;
        $rules = [
            'user_id' => 'not_present',
            'beginning_sum' => 'not_present',
            'balance' => 'not_present',
            'taxes' => 'not_present',
            'created_at' => 'not_present',
            'updated_at' => 'not_present',
            'month' => 'not_present',
//            'value' => 'numeric'
            'value' => ['regex:/^[0-9]{1,16}\.[0-9]{0,4}$/']
        ];

        $dataForValidation = [$request['variableName'] => $request['value']];
        $validator = Validator::make($dataForValidation, $rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ], 200);
        }


        $month = Month::where('id', $monthId)
            ->where('user_id', Auth::user()->id)
            ->with(['apartment'])
            ->first();

        $taxes = json_decode($month->taxes,true);

        $taxes['variables'][$request['variableName']] = $request['value'];

//        return var_dump(((json_encode($taxes, JSON_UNESCAPED_UNICODE))) );
//        $month->update(['taxes' => json_encode($taxes, JSON_UNESCAPED_UNICODE)]);

        $this->recalculateBeginningSum($month);

        return Response::json(['success' => true], 200);
    }

    public function mailTest()
    {
        Mail::send('emails.send', ['title' => 'title', 'content' => 'content'], function ($message)
        {

            $message->from('osmd09@mail.ru', 'Christian Nwamba');

            $message->to('addictedtothelove@gmail.com');

        });
    }
}
