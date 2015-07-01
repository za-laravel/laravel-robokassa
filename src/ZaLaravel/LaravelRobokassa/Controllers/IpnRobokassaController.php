<?php

namespace ZaLaravel\LaravelRobokassa\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

/**
 * Class IpnRobokassaController
 * @package ZaLaravel\LaravelRobokassa\Controllers
 */
class IpnRobokassaController extends Controller{

    public function getResult(Request $request)
    {
        $out_sum = $request->get('OutSum');
        $inv_id = $request->get('InvId');
        $sign = $request->get('SignatureValue');

        Log::info('Полученная сумма: '.$out_sum, 'номер  счета в магазине: '.$inv_id,
            'контр. сумма: '.$sign);

        return new Response('Ok' . $inv_id);
    }
}