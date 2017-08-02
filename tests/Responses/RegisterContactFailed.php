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
    private $code;
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $info;

    public function __construct($code, $status, $info)
    {
        $this->code = $code;
        $this->status = $status;
        $this->info = $info;
    }

    public function getBody(): Stream
    {
        return stream_for('{"code":"' . $this->code . '","status": "' . $this->status . '","info":"' . $this->info . '"}');
    }
}