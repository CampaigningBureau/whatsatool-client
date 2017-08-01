<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 14:45
 */

namespace CampaigningBureau\WhatsAToolClient\Test\Responses;


use CampaigningBureau\WhatsAToolClient\Msisdn;
use GuzzleHttp\Psr7\Stream;
use function GuzzleHttp\Psr7\stream_for;

class RegisterContactSuccessful implements GuzzleResponseMock
{

    /**
     * @var Msisdn the sim msisdn the number is registered to
     */
    private $simMsisdn;

    public function __construct($simMsisdn)
    {
        $this->simMsisdn = ($simMsisdn) ?? new Msisdn("436771234567");
    }

    public function getBody(): Stream
    {
        return stream_for('{"status":"ok","info":"contact added to channel","code":100,"simMsisdn":"' . $this->simMsisdn->get() . '"}');
    }
}