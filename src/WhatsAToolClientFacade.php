<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 31.07.17
 * Time: 22:40
 */

namespace CampaigningBureau\WhatsAToolClient;

use Illuminate\Support\Facades\Facade;

class WhatsAToolClientFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'whatsatool-client';
    }

}