<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 18/03/2017
 * Time: 16:52
 */

namespace WebLinks\Tests;


use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use WebLinks\Domain\User;

class AppTest extends WebTestCase
{
    /**
     * Checks that all application URLs load successfully
     *
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessfull($url)
    {
        $client = $this->createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    public function createApplication()
    {
        $app = new Application();

        require __DIR__.'/../../app/config/dev.php';
        require __DIR__.'/../../app/app.php';
        require __DIR__.'/../../app/routes.php';

        // Generate raw exceptions instead of HTML pages if errors occur
        unset($app['exception_handlers']);
        // Simulate sessions for testing
        $app['session.test'] = true;
        // Enable anonymous access to admin zone and submit link
        $app['security.access_rules'] = array();

        // Create a user for testing
        $user = new User();
        $user->setId(1);
        $app['user'] = $user;

        return $app;
    }

    public function provideUrls()
    {
        return array(
            array('/'),
            array('/login'),
            array('/submit'),
            array('/admin'),
            array('/admin/link/1/edit'),
            array('/admin/user/1/edit'),
            array('/api/links'),
            array('/api/link/1'),
        );
    }
}
