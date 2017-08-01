<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 10:46
 */

namespace CampaigningBureau\WhatsAToolClient\Test;

use CampaigningBureau\WhatsAToolClient\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;

class ClientTest extends TestCase
{

    private $client;

    public function setUp()
    {
        $this->client = new Client(new \GuzzleHttp\Client());
    }

    public function tearDown()
    {
        Mockery::close();
    }

    private function mockClientWithSuccessfulCall($url) {
        $guzzle_mock = Mockery::mock('client');
        $guzzle_mock->shouldReceive('request')
            ->withArgs(['GET', $url])
            ->andReturn()
    }

    private function getRegisterContactUrl($username, $password, $msisdn, $sendSms = 0, $channel = "")
    {
        $url = "https://wat.atms.at/ws/contact/do.php?username=$username&password=$password&action=registerContact&msisdn=$msisdn";
        if ($sendSms) {

        }
    }

}
