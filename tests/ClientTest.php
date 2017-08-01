<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 10:46
 */

namespace CampaigningBureau\WhatsAToolClient\Test;

use CampaigningBureau\WhatsAToolClient\Msisdn;
use CampaigningBureau\WhatsAToolClient\Test\Responses\RegisterContactFailed;
use CampaigningBureau\WhatsAToolClient\Test\Responses\RegisterContactSuccessful;
use CampaigningBureau\WhatsAToolClient\WhatsAToolClient;
use CampaigningBureau\WhatsAToolClient\WhatsAToolException;
use Mockery;

class ClientTest extends TestCase
{

    /**
     * @var WhatsAToolClient
     */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = new WhatsAToolClient(new \GuzzleHttp\Client());
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testRegisterContactReturnsSimMsisdnOnSuccess()
    {
        $simMsisdn = $this->getValidSimMsisdn();
        $msisdn = $this->getValidMsisdn();
        $channel = "some_channel";
        $sendSms = false;
        $this->mockClientWithSuccessfulRegisterContactCall($this->getRegisterContactUrl($msisdn, $channel, $sendSms), $simMsisdn);
        $this->assertEquals($this->client->registerContact($msisdn, $channel, $sendSms)->get(), $simMsisdn->get());
    }

    private function getValidSimMsisdn()
    {
        return new Msisdn('436771234567');
    }

    private function getValidMsisdn()
    {
        return new Msisdn('066488188122');
    }

    private function mockClientWithSuccessfulRegisterContactCall($url, $simMsisdn = null)
    {
        $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
        $guzzle_mock->shouldReceive('get')
            ->with(equalTo($url))
            ->andReturn(new RegisterContactSuccessful($simMsisdn));
        $this->client = new WhatsAToolClient($guzzle_mock);
    }

    /**
     * @param Msisdn $msisdn
     * @param string $channel
     * @param bool $sendSms
     * @return string
     */
    private function getRegisterContactUrl($msisdn, $channel = "", $sendSms = false)
    {
        $sendSms = (int)$sendSms;
        $url = "https://wat.atms.at/ws/contact/do.php?username=$this->username&password=$this->password&action=registerContact&msisdn={$msisdn->get()}&sendSms=$sendSms";
        if (!empty($channel)) {
            $url = $url . "&channel=$channel";
        }
        return $url;
    }

    public function testShouldCallCorrectUrlWithOnlyMsisdn()
    {
        $msisdn = $this->getValidMsisdn();
        $this->mockClientWithSuccessfulRegisterContactCall($this->getRegisterContactUrl($msisdn));
        $this->client->registerContact($msisdn);
        $this->assertTrue(true);
    }

    public function testShouldCallCorrectUrlWithOnlyMsisdnAndChannel()
    {
        $msisdn = $this->getValidMsisdn();
        $channel = "some_channel";
        $this->mockClientWithSuccessfulRegisterContactCall($this->getRegisterContactUrl($msisdn, $channel));
        $this->client->registerContact($msisdn, $channel);
        $this->assertTrue(true);
    }

    public function testShouldCallCorrectUrlWithSendSms()
    {
        $msisdn = $this->getValidMsisdn();
        $channel = "";
        $sendSms = true;
        $this->mockClientWithSuccessfulRegisterContactCall($this->getRegisterContactUrl($msisdn, $channel, $sendSms));
        $this->client->registerContact($msisdn, $channel, $sendSms);
        $this->assertTrue(true);
    }

    public function testShouldThrowWhatsAToolExceptionOnFailureWithCodeAndMessage()
    {
        $statuscode = 405;
        $msisdn = $this->getValidMsisdn();
        $info = 'no permission for edit contact';
        $this->mockClientWithFailedRegisterContactCall($this->getRegisterContactUrl($msisdn), $statuscode, $info);

        $this->expectException(WhatsAToolException::class);
        $this->expectExceptionCode($statuscode);
        $this->expectExceptionMessage($info);

        $this->client->registerContact($msisdn);
    }

    private function mockClientWithFailedRegisterContactCall($url, $statuscode, $info)
    {
        $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
        $guzzle_mock->shouldReceive('get')
            ->with(equalTo($url))
            ->andReturn(new RegisterContactFailed($statuscode, 'error', $info));
        $this->client = new WhatsAToolClient($guzzle_mock);
    }

}
