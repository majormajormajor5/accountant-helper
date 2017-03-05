<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Building;
use App\Month;
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
            ->where('month', '2017-03-01')
            ->with(['apartment', 'apartment.building', 'apartment.building.organization'])
            ->get();


        foreach ($months as $month) {
            $output = "";
            $output.= "<!DOCTYPE html>
					<html lang=\"ru\">
						<head>
	  						<meta charset=\"utf-8\">
	  						<title>Рахунок</title>
	  					<head>";
            $output.= "<body>";

            $output.= "Рахунок\n";
            $output.= "<p>За період з " . $month->month ." по $month->month\n</p>";
            //$output.= "За період з 16.03.2016 по 31.03.2016\n";

            $output.= "<p>Одержувач:\n</p>";
            $output.= "<p>ОСББ \"НАБЕРЕЖНИЙ КВАРТАЛ 62А\"</p>";
            $output.= "<p>Код ЄДРПОУ  40119773\n</p>";
            $output.= "<p>р/р 26001050270666\n</p>";
            $output.= "<p>КБ  \"ПРИВАТБАНК\"\n</p><p>МФО 305299\n</p>";


            $output.= "<p>Платник: о/р " . $month->apartment->id . "\n</p>"; //107 номер квартиры
            $output.= "<p>Набережна Перемоги б.62 А, кв. ". $month->apartment->number . " " . isset() $month->apartment->owners->first()->getFullName() . "\n</p>";


//            $output.= "<table border='1'><tr><td style=\"padding-right:100px\">Сальдо на початок періоду, грн: </td>   <td></td><td></td><td></td>   <td>$prev_balance_pad</td></tr>";
//
//
//
//            $output.= "<tr><td>Внесок</td><td>Кількість</td><td>од</td><td>Тариф,грн</td><td>Сума,грн</td></tr>";
//            $output.= "<tr><td>на Утримання будинку</td><td>$square_pad</td><td>кв.м</td><td>$tarif_pad</td><td>$housekeeping_bill_pad</td></tr>";
//            $output.= "<tr><td>На охорону</td><td></td><td></td><td></td><td>$guard_bill_pad</td></tr>";
//            $output.= "<tr><td>Нараховано за період</td><td></td><td></td><td></td><td>$beginning_sum_pad</td></tr>";
//            $output.= "<tr><td>Отримано оплат за період</td><td></td><td></td><td></td><td>$sum_pad</td></tr>";
//            $output.= "<tr><td>Сума до сплати</td><td></td><td></td><td></td><td><b>$balance</b></td></tr></table>";
//            $output.= "</body>";
//            $output.= "</html>";
        }

        echo $output;

        return;

        /*//$period = $this->input->post('bill_period');
        $period = '2017-01-01';
        $prev_period = new DateTime($period);
        $prev_period = $prev_period->sub(new DateInterval('P1M'))->format('Y-m-d');
        //echo $prev_period;
        $bills = $this->bill_model->get_all_bills($period);
        //var_dump($prev_period);
        var_dump($bills);

        $prev_ending_sum = $this->bill_model->get_prev_esum($prev_period);
        $prev_balance = $this->bill_model->get_prev_balance($prev_period);
        //var_dump($prev_ending_sum);
        $file = "/var/www/accountant.com/public_html/bills.html";
        if(file_exists($file))
        {
            unlink($file);
        }

        fopen($file, "w+");


        $i=0;
        foreach ($bills as $key => $bill)
        {
            $i++;
            //echo $prev_ending_sum[$key]->ending_sum;

            $tariff = $bill->tariff;
            $square = $bill->square;
            $housekeeping_bill = $bill->housekeeping_bill;
            $housekeeping_bill_pad = str_pad($housekeeping_bill, 7);
            $tarif_pad = str_pad("1.90", 6);
            $square_pad = str_pad($square, 6);
            $beginning_sum_pad = str_pad($bill->beginning_sum, 7);
            $guard_bill_pad = str_pad($bill->guard_bill, 7);
            //$sum_pad = str_pad($prev_ending_sum[$key]->ending_sum, 7);
            $sum_pad = str_pad($bill->ending_sum, 7);
            $prev_balance_pad = str_pad($prev_balance[$key]->balance, 7);
            $apartment = $bill->id;
            $balance = $bill->balance;
            $period = date('d.m.Y', strtotime($bill->period));
            $cur_period = new DateTime($period);
            $period_end = $cur_period->add(new DateInterval('P1M'))->sub(new DateInterval('P1D'))->format('d.m.Y');
            $owner = $bill->owner;

            $output = "";
            $output.= "<!DOCTYPE html>
					<html lang=\"ru\">
						<head>
	  						<meta charset=\"utf-8\">
	  						<title>Рахунок</title>
	  					<head>";
            $output.= "<body>";

            $output.= "Рахунок\n";
            $output.= "<p>За період з $period по $period_end\n</p>";
            //$output.= "За період з 16.03.2016 по 31.03.2016\n";

            $output.= "<p>Одержувач:\n</p>";
            $output.= "<p>ОСББ \"НАБЕРЕЖНИЙ КВАРТАЛ 62А\"</p>";
            $output.= "<p>Код ЄДРПОУ  40119773\n</p>";
            $output.= "<p>р/р 26001050270666\n</p>";
            $output.= "<p>КБ  \"ПРИВАТБАНК\"\n</p><p>МФО 305299\n</p>";


            $output.= "<p>Платник: о/р "."$apartment\n</p>"; //107 номер квартиры
            $output.= "<p>Набережна Перемоги б.62 А, кв. "." $apartment "." $owner\n</p>";


            $output.= "<table border='1'><tr><td style=\"padding-right:100px\">Сальдо на початок періоду, грн: </td>   <td></td><td></td><td></td>   <td>$prev_balance_pad</td></tr>";



            $output.= "<tr><td>Внесок</td><td>Кількість</td><td>од</td><td>Тариф,грн</td><td>Сума,грн</td></tr>";
            $output.= "<tr><td>на Утримання будинку</td><td>$square_pad</td><td>кв.м</td><td>$tarif_pad</td><td>$housekeeping_bill_pad</td></tr>";
            $output.= "<tr><td>На охорону</td><td></td><td></td><td></td><td>$guard_bill_pad</td></tr>";
            $output.= "<tr><td>Нараховано за період</td><td></td><td></td><td></td><td>$beginning_sum_pad</td></tr>";
            $output.= "<tr><td>Отримано оплат за період</td><td></td><td></td><td></td><td>$sum_pad</td></tr>";
            $output.= "<tr><td>Сума до сплати</td><td></td><td></td><td></td><td><b>$balance</b></td></tr></table>";
            $output.= "</body>";
            $output.= "</html>";


            $handle = fopen("/var/www/accountant.com/public_html/bills/"."$apartment".".html", "w+");
            fwrite($handle, $output);
            fclose($handle);
            //Проверка на пустой или отсутствующий емаил
            if(($bill->email !== NULL) && ($bill->email !== ""))
            {
                //$this->mail($apartme/nt, $bill->email);
                echo "Высылаю на $bill->email \n";
                $this->mail($apartment, $bill->email);
            }


        }*/

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
}
