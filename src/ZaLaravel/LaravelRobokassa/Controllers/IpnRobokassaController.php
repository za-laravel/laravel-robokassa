<?php

namespace ZaLaravel\LaravelRobokassa\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\User;
/**
 * Class IpnRobokassaController
 * @package ZaLaravel\LaravelRobokassa\Controllers
 */
class IpnRobokassaController extends Controller{


    public function getResult(Request $request)
    {
        $user = $request->user()->id;
        $out_sum = $request->get('OutSum');
        $inv_id = $request->get('InvId');
        $password2 = config('roboconfig.testPassword2');

        if(Payment::where('uid', '=', $inv_id) && Payment::where('balance', '=', $out_sum))
        {
            $payment = Payment::where('uid', '=', $inv_id)->first();
            $payment->status = 1;
            $payment->update();
            $addBalanceToUser = User::find($user);
            $addBalanceToUser->balance += $out_sum;
            $addBalanceToUser->update();
        }else
        {
            \Session::flash('message', 'Подпись или сумма не совпадает, повторите попытку.');
            return redirect()->action('ProfileController@index');
        }
        \Session::flash('message', "Ваш баланс пополнен на $out_sum рублей.");
        return redirect()->action('ProfileController@index');
    }
}