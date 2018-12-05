<?php

namespace App\Http\Controllers;

use App\Services\Electrum;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use Illuminate\Http\Request;

class ElectrumController extends Controller
{
    protected $electrum;

    public function __construct(Electrum $electrum)
    {
        $this->electrum = $electrum;
    }

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
