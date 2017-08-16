<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 31.07.17
 * Time: 23:14
 */

namespace CampaigningBureau\WhatsAToolClient;


use InvalidArgumentException;

class Msisdn
{

    const VALID_COUNTRY_CODES = [
        '1', '7', '20', '27', '30', '31', '32', '33', '34', '36', '39', '40', '41', '43', '44', '45', '46', '47', '48',
        '49', '51', '52', '53', '54', '55', '56', '57', '58', '60', '61', '62', '63', '64', '65', '66', '81', '82',
        '84', '86', '90', '91', '92', '93', '94', '95', '98', '212', '213', '216', '218', '220', '221', '222', '223',
        '224', '225', '226', '227', '228', '229', '230', '231', '232', '233', '234', '235', '236', '237', '238', '239',
        '240', '241', '242', '243', '244', '245', '248', '249', '250', '251', '252', '253', '254', '255', '256', '257',
        '258', '260', '261', '262', '263', '264', '265', '266', '267', '268', '269', '290', '291', '297', '298', '299',
        '350', '351', '352', '353', '354', '355', '356', '357', '358', '359', '370', '371', '372', '373', '374', '375',
        '376', '377', '378', '380', '381', '382', '385', '386', '387', '389', '420', '421', '423', '500', '501', '502',
        '503', '504', '505', '506', '507', '508', '509', '590', '591', '592', '593', '595', '597', '598', '599', '670',
        '672', '673', '674', '675', '676', '677', '678', '679', '680', '681', '682', '683', '685', '686', '687', '688',
        '689', '690', '691', '692', '850', '852', '853', '855', '856', '870', '880', '886', '960', '961', '962', '963',
        '964', '965', '966', '967', '968', '971', '972', '973', '974', '975', '976', '977', '992', '993', '994', '995',
        '996', '998'];

    /**
     *
     * @var string
     */
    private $msisdn;

    public function __construct($phonenumber, $country_code = null)
    {
        if (Msisdn::validatePhonenumber($phonenumber) === false) {
            throw new InvalidArgumentException(
                'The supplied phonenumber is not valid. ' .
                'You can use the `Msisdn::validatePhonenumber()` method ' .
                'to validate the phonenumber passed.'
            );
        }

        $this->msisdn = Msisdn::clean($phonenumber);
        if (Msisdn::hasLocalCountryCode($this->msisdn)) {
            if ($country_code === null) {
                $country_code = config('whatsatool.default_country_code');
            }
            $this->replaceLocalCountryCode($country_code);
        }
    }

    /**
     * @param $phonenumber
     * @return bool true if valid phonenumber
     */
    public static function validatePhonenumber($phonenumber)
    {
        $cleaned_number = Msisdn::clean($phonenumber);

        return !empty($cleaned_number) &&
            is_numeric($cleaned_number) &&
            strlen($cleaned_number) <= 15 &&
            Msisdn::validateCountryCode($cleaned_number);
    }

    /**
     * Cleans the given phonenumber:
     *   - strips all non-numberic characters
     *   - trims leading 00
     *   - remove (0) in the number
     *
     * does NOT trim or replace local CC 0, as it is needed for internal storage.
     *
     * @param string $phonenumber
     * @return string the cleaned phonenumber
     */
    private static function clean(string $phonenumber)
    {
        // remove (0) in the number, if not at the beginning
        $phonenumber = preg_replace("/(?<!^)\(0\)/", "", $phonenumber);

        // strip all non-numeric characters
        $phonenumber = preg_replace("/[^0-9]/", "", $phonenumber);

        // strip leading 00
        if (preg_match("/^00/", $phonenumber)) {
            $phonenumber = substr($phonenumber, 2);
        }

        return $phonenumber;
    }

    private static function validateCountryCode($phonenumber)
    {
        if (Msisdn::hasLocalCountryCode($phonenumber))
            return true;

        // check if phonenumber starts with valid country code
        foreach (Msisdn::VALID_COUNTRY_CODES as $COUNTRY_CODE) {
            if (strpos($phonenumber, $COUNTRY_CODE) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $phonenumber
     * @return bool
     */
    private static function hasLocalCountryCode($phonenumber): bool
    {
        return preg_match("/^0[^0]/", $phonenumber);
    }

    /**
     * replaces the first character with the country code,
     * regardless if 0 (local cc) or not.
     *
     * @param string $country_code
     */
    private function replaceLocalCountryCode(string $country_code)
    {
        $this->msisdn = $country_code . substr($this->msisdn, 1);
    }

    public function get()
    {
        return $this->msisdn;
    }
}