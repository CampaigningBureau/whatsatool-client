<?php
/**
 * Created by PhpStorm.
 * User: christophschleifer
 * Date: 01.08.17
 * Time: 10:03
 */

namespace CampaigningBureau\WhatsAToolClient\Test;

use CampaigningBureau\WhatsAToolClient\WhatsAToolClientFacade;
use CampaigningBureau\WhatsAToolClient\WhatsAToolClientProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{

    protected $username = 'user';
    protected $password = 'passwort';

    /**
     * @param \Illuminate\Foundation\Application $application
     * @return array
     */
    protected function getPackageProviders($application)
    {
        return [WhatsAToolClientProvider::class];
    }

    protected function getPackageAliases($application)
    {
        return [
            'WhatsATool' => WhatsAToolClientFacade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('whatsatool.default_country_code', '43');
        $app['config']->set('whatsatool.username', $this->username);
        $app['config']->set('whatsatool.password', $this->password);
    }
}