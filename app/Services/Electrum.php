<?php

namespace App\Services;


use Datto\JsonRpc\Client;

/**
 * Class Electrum
 * @package App\Services
 */
class Electrum
{

    /**
     * @var string
     */
    protected $host;
    /**
     * @var string
     */
    protected $port;
    /**
     * @var Client
     */
    protected $client;

    /**
     * Electrum constructor.
     */
    public function __construct()
    {
        $this->host = config('electrum.host', 'ssl://electrum.nute.net');
        $this->port = config('electrum.port', 50002);
        $this->client = new Client();
    }

    /**
     * @param string $address
     * @return bool|\Datto\JsonRpc\Response[]
     * @throws \ErrorException
     */
    public function getAddressBalance(string $address)
    {
        return $this->sendRequest('blockchain.address.get_balance', [$address]);
    }

    /**
     * @param string $address
     * @return bool|\Datto\JsonRpc\Response[]
     * @throws \ErrorException
     */
    public function getAddressHistory(string $address)
    {
        return $this->sendRequest('blockchain.address.get_history', [$address]);
    }

    /**
     * @param string $address
     * @return bool|\Datto\JsonRpc\Response[]
     * @throws \ErrorException
     */
    public function getAddressMempool(string $address)
    {
        return $this->sendRequest('blockchain.address.get_mempool', [$address]);
    }

    /**
     * @param string $txid
     * @return bool|\Datto\JsonRpc\Response[]
     * @throws \ErrorException
     */
    public function getRawTx(string $txid)
    {
        return $this->sendRequest('blockchain.transaction.get', [$txid]);
    }

    /**
     * @param string $txid
     * @return string|false
     */
    public function getTx(string $txid)
    {
        $data = rescue(function () use ($txid) {
            return file_get_contents("https://blockchain.info/rawtx/$txid");
        }, function () {
            return false;
        });

        return $data;
    }

    /**
     * @param $method
     * @param array $params
     * @return bool|\Datto\JsonRpc\Response[]
     * @throws \ErrorException
     */
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
