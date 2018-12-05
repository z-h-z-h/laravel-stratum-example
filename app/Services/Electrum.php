<?php

namespace App\Services;


use Datto\JsonRpc\Client;

class Electrum
{

    protected $host;
    protected $port;
    protected $client;

    public function __construct()
    {
        $this->host = config('electrum.host', 'ssl://electrum.nute.net');
        $this->port = config('electrum.port', 50002);
        $this->client = new Client();
    }

    public function getAddressBalance(string $address)
    {
        return $this->sendRequest('blockchain.address.get_balance', [$address]);
    }

    public function getAddressHistory(string $address)
    {
        return $this->sendRequest('blockchain.address.get_history', [$address]);
    }

    public function getAddressMempool(string $address)
    {
        return $this->sendRequest('blockchain.address.get_mempool', [$address]);
    }

    public function getRawTx(string $txid)
    {
        return $this->sendRequest('blockchain.transaction.get', [$txid]);
    }

    public function getTx(string $txid)
    {

        $data = rescue(function () use ($txid) {
            return file_get_contents("https://blockchain.info/rawtx/$txid");
        }, function () {
            return false;
        });

        return $data;
    }

    public function sendRequest($method, $params = [])
    {
        $json = $this->client->query('myid', $method, $params)->encode();

        $connection = fsockopen($this->host, $this->port);

        if (!$connection) {
            return false;
        }

        if (!fwrite($connection, $json . "\r\n", strlen($json . "\r\n"))) {
            return false;
        }

        if (!$rawData = fgets($connection)) {
            return false;
        }

        return $this->client->decode($rawData);
    }
}
