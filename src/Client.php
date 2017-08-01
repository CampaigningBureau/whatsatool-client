<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 31.07.17
 * Time: 22:44
 */

namespace CampaigningBureau\WhatsAToolClient;


use GuzzleHttp\Client as GuzzleClient;

class Client
{

    /**
     * @var GuzzleClient
     */
    private $guzzle;

    public function __construct(GuzzleClient $guzzle)
    {
        if (!config('whatsatool.username') || !config('whatsatool.password')) {
            throw new \Exception(
                'no username and/or password defined in the whatsatool config file'
            );
        }

        $this->guzzle = $guzzle;
    }

    public function registerContact(Msisdn $msisdn, string $channel = "", bool $sendSMS = false) {
        //TODO: send to Whatsatool

        // 1. send to whatsatool
        // https://wat.atms.at/ws/contact/do.php?username=watuser1&password=er4m9xs2f7hk&action=registerContact&msisdn=436641234567

        // 2. handle response
        // {"status":"ok","info":"contact added to channel","code":100,"simMsisdn":"436771234567"}
    }
}