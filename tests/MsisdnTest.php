<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 31.07.17
 * Time: 23:18
 */

namespace CampaigningBureau\WhatsAToolClient\Test;

use CampaigningBureau\WhatsAToolClient\Msisdn;

class MsisdnTest extends TestCase
{

    private $valid_phonenumbers = [
        ["4366488188122", "4366488188122"],
        ["+4366488188122", "4366488188122"],
        ["004366488188122", "4366488188122"],
        ["066488188122", "4366488188122"],
        ["+43 664 881 881 22", "4366488188122"],
        ["+43 - 664/8818812-2", "4366488188122"],
        ["066488188122", "4366488188122"],
        ["+43 (0) 66488188122", "4366488188122"],
        ["+43(0)66488188122", "4366488188122"],
        ["(0)66488188122", "4366488188122"],
    ];
    private $invalid_phonenumbers = [
        "asdf",
        "",
        "2866488188122", //invalid countrycode 28
        "0",
    ];

    public function testValidatePhonenumberShouldReturnTrueOnValidPhonenumber()
    {
        foreach ($this->valid_phonenumbers as $phonenumber) {
            $this->assertTrue(Msisdn::validatePhonenumber($phonenumber[0]), "Valid Phonenumber $phonenumber[0] is wrongfully invalid");
        }
    }

    public function testValidatePhonenumberShouldReturnFalseOnInvalidPhonenumber()
    {
        foreach ($this->invalid_phonenumbers as $invalid_phonenumber) {
            $this->assertFalse(Msisdn::validatePhonenumber($invalid_phonenumber), "Invalid Phonenumber $invalid_phonenumber is wrongfully valid");
        }
    }

    public function testNewMsisdnFromValidPhonenumberShouldCreateRightMsisdn()
    {
        foreach ($this->valid_phonenumbers as $phonenumber) {
            $this->assertEquals($phonenumber[1], (new Msisdn($phonenumber[0]))->get());
        }
    }
}
