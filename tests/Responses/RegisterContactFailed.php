<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 14:45
 */

namespace CampaigningBureau\WhatsAToolClient\Test\Responses;


use GuzzleHttp\Psr7\Stream;
use function GuzzleHttp\Psr7\stream_for;

class RegisterContactFailed implements GuzzleResponseMock
{

    /**
     * @var string
     */
    private $statusCode;
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $info;

    public function __construct($statusCode, $status, $info)
    {
        $this->statusCode = $statusCode;
        $this->status = $status;
        $this->info = $info;
    }

    public function getBody(): Stream
    {
        return stream_for('{"statuscode":"' . $this->statusCode . '","status": "' . $this->status . '","info":"' . $this->info . '"}');
    }
}