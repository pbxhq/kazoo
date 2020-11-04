<?php

namespace Pbxhq\Kazoo;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;


class KazooController extends Controller
{

    public $baseUrl;
    public $apiKey;
    public $authToken;
    public $masterId;
    public $resellerId;
    public $smartpbxid;

    public function __construct()
    {
        $this->baseUrl = env('KAZOO_BASEURL', null);
        $this->apiKey = env('KAZOO_APIKEY', null);
        $this->resellerId = env('KAZOO_RESELLER_ACCT', null);
        $authData = $this->fetchAuthToken();
        $this->authToken = $authData['authToken'];
        $this->masterId = $authData['masterId'];
    }

    public function fetchAuthToken()
    {
        if (!$this->authToken) {
            $this->authToken = $this->authTokenKazoo();
        }
        return $this->authToken;
    }




    public function kazooPatch($url, array $payload)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'X-Auth-Token' => $this->authToken
        ])->patch($this->baseUrl . $url, $payload);
        $body = collect(json_decode($response->body()));
        return $body->get('data');
    }

    public function kazooDelete($url)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'X-Auth-Token' => $this->authToken
        ])->put($this->baseUrl . $url);
        $body = collect(json_decode($response->body()));
        return $body->get('status');
    }

    public function kazooPut($url, array $payload)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'X-Auth-Token' => $this->authToken
        ])->put($this->baseUrl . $url, $payload);
        $body = json_decode($response->body(), true);
        $return = array();
        if ($body['status'] == 'success') {
            $return = array(
                "success" => true,
                "data" => $body['data']
            );
        } else {
            $return = array(
                "success" => false,
                "error" => $body['data']
            );
        }
        //$collect = collect($return);
        return collect($return);
    }


    public function kazooPost($url, array $payload)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'X-Auth-Token' => $this->authToken
        ])->post($this->baseUrl . $url, $payload);
        $body = json_decode($response->body(), true);
        $return = array();
        if ($body['status'] == 'success') {
            $return = array(
                "success" => true,
                "data" => $body['data']
            );
        } else {
            $return = array(
                "success" => false,
                "error" => $body['data']
            );
        }
        return collect($return);
    }


    public function kazooGet($url)
    {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'X-Auth-Token' => $this->authToken
        ])->get($this->baseUrl . $url);
        $body = collect(json_decode($response->body()));
        return $body->get('data');
    }


    protected function authTokenKazoo()
    {
        $payload = array('data' => array(
            'api_key' => $this->apiKey
        ));
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ])->put($this->baseUrl . '/api_auth', $payload);

        $body = collect($response->json());
        $account_id = collect($body->get('data'));
        $return['authToken'] = $body->get('auth_token');
        $return['masterId'] = $account_id->get('account_id');
        return $return;
    }
}
