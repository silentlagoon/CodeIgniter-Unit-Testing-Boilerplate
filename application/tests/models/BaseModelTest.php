<?php

/**
 * @group Model
 */

class BaseModelTest extends CIUnit_TestCase
{
    private $base_model;

    public function setUp()
    {
        parent::setUp();
        $this->CI->load->model('Base_Model');
        $this->base_model = $this->CI->Base_Model;
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function test_create_calls_db_insert_with_correct_params()
    {
        $mock = $this->getMockBuilder('CI_DB_active_record')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->any())
            ->method('insert')
            ->with($this->equalTo('table'), $this->equalTo(array('id' => 9)));

        $this->base_model->_table = 'table';
        $this->base_model->db = $mock;

        $this->base_model->create(array('id' => 7));
    }


//    public function test_int_value_find_one_method()
//    {
//        $this->_pcm->_table = 'test';
//        return $this->_pcm->find_one($this->integer);
//    }
//
//    /**
//     * @depends test_array_value_find_one_method
//     */
//    public function test_if_array_value_is_empty_find_one_method($condition)
//    {
//        $this->assertNotEmpty($condition);
//    }
//
//    /**
//     * @depends test_int_value_find_one_method
//     */
//    public function test_if_int_value_is_empty_find_one_method($condition)
//    {
//        $this->assertNotEmpty($condition);
//    }
}