<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 31.07.17
 * Time: 22:44
 */

namespace CampaigningBureau\WhatsAToolClient;


use GuzzleHttp\Client as GuzzleClient;

class WhatsAToolClient
{

    /**
     * @var GuzzleClient
     */
    private $guzzle;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;

    public function __construct(GuzzleClient $guzzle)
    {
        if (!config('whatsatool.username') || !config('whatsatool.password')) {
            throw new \Exception(
                'no username and/or password defined in the whatsatool config file'
            );
        }

        $this->guzzle = $guzzle;
        $this->username = config('whatsatool.username');
        $this->password = config('whatsatool.password');
    }

    /**
     * @param Msisdn $msisdn
     * @param string $channel
     * @param bool $sendSMS
     * @return Msisdn
     *
     * @throws WhatsAToolException
     */
    public function registerContact(Msisdn $msisdn, string $channel = "", bool $sendSMS = false)
    {

        $url = $this->getRegisterContactUrl($msisdn, $channel, $sendSMS);

        // do the request and throw exception on failure
        $data = $this->doRequest($url);

        // otherwise we got OK and can return the sim Msisdn the number is registered at.
        // Ok-Response looks like this: {"status":"ok","info":"contact added to channel","code":100,"simMsisdn":"436771234567"}
        return new Msisdn($data->simMsisdn);

    }

    /**
     * @param Msisdn $msisdn
     * @param string $channel
     * @param bool $sendSms
     *
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

    /**
     * @param $url
     * @return \StdClass json_data
     * @throws WhatsAToolException
     */
    private function doRequest($url)
    {
        $response = $this->guzzle->get($url);

        $data = json_decode((string)$response->getBody());

        if ($data->status === 'error')
            throw new WhatsAToolException($data->info, $data->code);

        return $data;
    }
}