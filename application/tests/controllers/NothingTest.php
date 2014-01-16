<?php

/**
 * @group Controller
 */

class NothingTest extends CIUnit_TestCase
{
    public $CI;

    public function setUp()
    {
        $this->CI = set_controller('nothing');
    }

    public function tearDown()
    {}

    function testNothing()
    {
        $this->CI->index();
        $output = output();
        $this->assertSame('1', $output);
    }
}