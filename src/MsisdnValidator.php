<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 31.07.17
 * Time: 23:07
 */

namespace CampaigningBureau\WhatsAToolClient;


class MsisdnValidator
{

    /**
     * Laravel Validator
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        return Msisdn::validatePhonenumber($value);
    }

}