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
use WhatsATool;

class ClientTest extends TestCase
{

    /**
     * @var WhatsAToolClient
     */
    private $client;
    /**
     * @var string
     */
    private $validPhonenumber;
    /**
     * @var Msisdn
     */
    private $validMsisdn;
    /**
     * @var Msisdn
     */
    private $validSimMsisdn;

    public function setUp()
    {
        parent::setUp();
        $this->validPhonenumber = '066488188122';
        $this->validMsisdn = new Msisdn($this->validPhonenumber);
        $this->validSimMsisdn = new Msisdn('436771234567');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testShouldProvideAFacade()
    {
        $this->app->bind(WhatsAToolClient::class, function () {
            $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
            $guzzle_mock->shouldReceive('get')
                ->andReturn(new RegisterContactSuccessful());
            return new WhatsAToolClient($guzzle_mock);
        });
        WhatsATool::registerContact($this->validMsisdn);

        $this->assertTrue(true);
    }

    public function testRegisterContactReturnsSimMsisdnOnSuccess()
    {
        // arrange
        $channel = "some_channel";
        $sendSms = false;
        $url = $this->getRegisterContactUrl($this->validMsisdn, $channel, $sendSms);
        $this->mockClientWithSuccessfulRegisterContactCall($url, $this->validSimMsisdn);

        // act
        $registeredOnSimMsisdn = WhatsATool::registerContact($this->validMsisdn, $channel, $sendSms);

        // assert
        $this->assertEquals($registeredOnSimMsisdn->get(), $this->validSimMsisdn->get());
    }

    public function testShouldCallCorrectUrlWithOnlyMsisdn()
    {
        // arrange
        $url = $this->getRegisterContactUrl($this->validMsisdn);
        $this->mockClientWithSuccessfulRegisterContactCall($url);

        // act
        WhatsATool::registerContact($this->validMsisdn);

        // assert -- only assert the correct url is called in the guzzle mock object
        $this->assertTrue(true);
    }

    public function testShouldCallCorrectUrlWithOnlyMsisdnAndChannel()
    {
        // arrange
        $channel = "some_channel";
        $url = $this->getRegisterContactUrl($this->validMsisdn, $channel);
        $this->mockClientWithSuccessfulRegisterContactCall($url);

        // act
        WhatsATool::registerContact($this->validMsisdn, $channel);

        // assert -- only assert the correct url is called in the guzzle mock object
        $this->assertTrue(true);
    }

    public function testShouldCallCorrectUrlWithSendSms()
    {
        // arrange
        $channel = "";
        $sendSms = true;
        $url = $this->getRegisterContactUrl($this->validMsisdn, $channel, $sendSms);
        $this->mockClientWithSuccessfulRegisterContactCall($url);

        // act
        WhatsATool::registerContact($this->validMsisdn, $channel, $sendSms);

        // assert -- only assert the correct url is called in the guzzle mock object
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldThrowWhatsAToolExceptionOnFailureWithCodeAndMessage()
    {
        // arrange
        $statuscode = 405;
        $info = 'no permission for edit contact';
        $url = $this->getRegisterContactUrl($this->validMsisdn);
        $this->mockClientWithFailedRegisterContactCall($url, $statuscode, $info);

        // assert -- exception need to be setup before
        $this->expectException(WhatsAToolException::class);
        $this->expectExceptionCode($statuscode);
        $this->expectExceptionMessage($info);

        // act
        WhatsATool::registerContact($this->validMsisdn);
    }

    private function mockClientWithSuccessfulRegisterContactCall($url, $simMsisdn = null)
    {
        $this->app->bind(WhatsAToolClient::class, function () use ($url, $simMsisdn) {
            $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
            $guzzle_mock->shouldReceive('get')
                ->with(equalTo($url))
                ->andReturn(new RegisterContactSuccessful($simMsisdn));
            return new WhatsAToolClient($guzzle_mock);
        });
    }

    private function mockClientWithFailedRegisterContactCall($url, $statuscode, $info)
    {
        $this->app->bind(WhatsAToolClient::class, function () use ($url, $statuscode, $info) {
            $guzzle_mock = Mockery::mock('GuzzleHttp\Client');
            $guzzle_mock->shouldReceive('get')
                ->with(equalTo($url))
                ->andReturn(new RegisterContactFailed($statuscode, 'error', $info));
            return new WhatsAToolClient($guzzle_mock);
        });
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

}
