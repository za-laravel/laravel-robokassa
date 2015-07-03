<?php

namespace ZaLaravel\LaravelRobokassa\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
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
        $checksum = $request->get('SignatureValue');
        $password2 = config('roboconfig.testPassword2');

        if (strtolower($checksum) == strtolower(md5($out_sum.":".$inv_id.":".$password2))) {
            if (Payment::where('uid', '=', $inv_id) && Payment::where('balance', '=', $out_sum)) {
                try {
                    DB::connection()->getPdo()->beginTransaction();
                    $payment = Payment::where('uid', '=', $inv_id)->first();
                    $payment->status = 1;
                    $payment->update();
                    $addBalanceToUser = User::find($user);
                    $addBalanceToUser->balance += $out_sum;
                    $addBalanceToUser->update();
                    DB::connection()->getPdo()->commit();
                } catch (\PDOException $e) {

                    \Session::flash('message', "$e->getMessage()");
                    DB::connection()->getPdo()->rollBack();
                }
            }
        }
        return redirect()->action('ProfileController@index');
    }
}