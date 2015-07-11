<?php

namespace ZaLaravel\LaravelRobokassa\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Payment;
use Illuminate\Http\Request;
use DB;

/**
 * Class PaymentController
 * @package ZaLaravel\LaravelRobokassa\Controllers
 */
class PaymentController extends Controller {

	public function createPayment(Request $request)
    {
        \Auth::user();
        $user = $request->user()->id;
        $sum = $request->get('OutSum');
        $mrh_login = config('roboconfig.testLogin');
        $mrh_pass1 = config('roboconfig.testPassword1');
        $inv_id = mt_rand();
        $inv_desc = 'Пополнение баланса';
        $crc = md5($mrh_login.":".$sum.":".$inv_id.":".$mrh_pass1);

        if($sum != 0) {
            try {
                DB::connection()->getPdo()->beginTransaction();
                    $payment = new Payment();
                    $payment->uid = $inv_id;
                    $payment->user_id = $user;
                    $payment->balance = $sum;
                    $payment->description = $inv_desc;
                    $payment->operation = '+';
                    $payment->save();
                DB::connection()->getPdo()->commit();
            } catch (\PDOException $e){

                print $e->getMessage();
                DB::connection()->getPdo()->rollBack();
            }
        }
       /* return redirect()->action('ZaLaravel\LaravelRobokassa\Controllers\IpnRobokassaController@getResult',
            array('OutSum' => $sum, 'InvId' => $inv_id, 'SignatureValue' => $crc));*/
       /*header("Location: https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&OutSum=$sum&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc");*/
        header("Location: http://test.robokassa.ru/Index.aspx?MrchLogin=$mrh_login&OutSum=$sum&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc");
    }
}
