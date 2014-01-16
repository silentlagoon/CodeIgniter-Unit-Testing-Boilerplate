<?php

/**
 * @group Model
 */

class TestTest extends CIUnit_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function test_if_find_one_returns_object()
    {
        $query_mock = $this->getMockBuilder('FakeModel')
            ->disableOriginalConstructor()
            ->getMock();
        $query_mock->expects($this->any())
            ->method('getSample')
            ->with($this->equalTo('array'))
            ->will($this->returnValue($this->getSample));
    }
}

class FakeModel
{
    public function getSample(){
        return $this;
    }
}