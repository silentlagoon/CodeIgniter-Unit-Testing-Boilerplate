<?php

/**
 * @group Controller
 */

class SomeControllerTest extends CIUnit_TestCase
{
    public function setUp()
    {
        $_SERVER['HTTP_HOST'] = "lgsm";
        $_SERVER['REMOTE_ADDR'] = "127.0.0.1";

        // Set the tested controller
        $this->CI = set_controller('welcome');
    }

    public function tearDown()
    {
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['REMOTE_ADDR']);
    }

    function testWelcomeController()
    {
        // Call the controllers method
        $this->CI->index();

        // Fetch the buffered output
        $out = output();

        // Check if the content is OK
        $this->assertSame(0, preg_match('/(error|notice)/i', $out));
    }
}
