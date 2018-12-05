<?php

namespace App\Http\Controllers;

use App\Services\Electrum;
use Illuminate\Http\Request;

/**
 * Class ElectrumController
 * @package App\Http\Controllers
 */
class ElectrumController extends Controller
{
    /**
     * @var Electrum
     */
    protected $electrum;

    /**
     * ElectrumController constructor.
     * @param Electrum $electrum
     */
    public function __construct(Electrum $electrum)
    {
        $this->electrum = $electrum;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \ErrorException
     */
    public function getAddressData(Request $request)
    {
        $address = $request->input('address');

        if(!$address){
            return redirect('/');
        }

        $balance = $this->electrum->getAddressBalance($address)[0]->getResult();
        $history = $this->electrum->getAddressHistory($address)[0]->getResult();
        $unconfirmed = $this->electrum->getAddressMempool($address)[0]->getResult();

        return view('address', compact('address','balance', 'history', 'unconfirmed'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \ErrorException
     */
    public function getTxData(Request $request)
    {
        $txid = $request->input('txid');

        if(!$txid){
            return redirect('/');
        }

        $raw = $this->electrum->getRawTx($txid)[0]->getResult();
        $data = $this->electrum->getTx($txid);

        return view('transaction', compact('txid','raw','data'));
    }
}
