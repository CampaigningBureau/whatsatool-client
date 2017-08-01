<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 14:46
 */

namespace CampaigningBureau\WhatsAToolClient\Test\Responses;


use GuzzleHttp\Psr7\Stream;

interface GuzzleResponseMock
{
    public function getBody(): Stream;
}