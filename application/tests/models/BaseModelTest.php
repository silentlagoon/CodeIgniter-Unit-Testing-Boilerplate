<?php

/**
 * @group Model
 */

class BaseModelTest extends CIUnit_TestCase
{
    private $base_model;
    private $mock;

    public function setUp()
    {
        parent::setUp();
        $this->mock = NULL;
        $this->CI->load->model('Base_Model');
        $this->base_model = $this->CI->Base_Model;
        $this->mock = $this->getMockBuilder('CI_DB_active_record')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->mock = NULL;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_create_throws_invalid_argument_exception()
    {
        $this->base_model->create('parameter');
    }

    public function test_create_calls_db_insert_with_correct_params()
    {
        $this->mock->expects($this->any())
            ->method('insert')
            ->with(
                $this->equalTo('table'),
                $this->equalTo(array('id' => 9))
            );
        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->create(array('id' => 9));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_find_one_throw_invalid_argument_exception()
    {
        $this->base_model->find_one('parameter');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_one_throws_unexpected_value_exception_if_query_is_null()
    {
        $this->mock->expects($this->any())->method('get_where');

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_one(9);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_one_calls_get_where_with_correct_parameters_when_integer_is_passed()
    {
        $this->mock->expects($this->any())
            ->method('get_where')
            ->with(
                $this->equalTo('table'),
                $this->equalTo(array('id' => 9)),
                $this->equalTo(1)
            );

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_one(9);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_one_calls_get_where_with_correct_parameters_when_array_is_passed()
    {
        $this->mock->expects($this->any())
            ->method('get_where')
            ->with(
                $this->equalTo('table'),
                $this->equalTo(array('name' => 'Alex')),
                $this->equalTo(1)
            );

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_one(array('name' => 'Alex'));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_one_calls_get_where_with_table_from_options_when_incoming_params_is_integer()
    {
        $this->mock->expects($this->any())
            ->method('get_where')
            ->with(
                $this->equalTo('another_table'),
                $this->equalTo(array('id' => 9)),
                $this->equalTo(1)
            );

        $this->base_model->db = $this->mock;
        $this->base_model->find_one(9, array('table' => 'another_table'));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_one_calls_get_where_with_table_from_options_when_incoming_params_is_array()
    {
        $this->mock->expects($this->any())
            ->method('get_where')
            ->with(
                $this->equalTo('another_table'),
                $this->equalTo(array('name' => 'Alex')),
                $this->equalTo(1)
            );

        $this->base_model->db = $this->mock;
        $this->base_model->find_one(array('name' => 'Alex'), array('table' => 'another_table'));
    }

    public function test_find_one_calls_result_with_type_equals_array()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->with($this->equalTo('array'));
            //->will($this->returnValue(array(100)));

        $this->mock->expects($this->any())
                ->method('get_where')
                ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_one(9, array('return_type' => 'array'));
        //$this->assertEquals(100, $result);
    }

    public function test_find_one_calls_result_with_type_equals_object()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->with($this->equalTo('object'));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_one(9);
    }

    public function test_find_one_returns_null()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->will($this->returnValue(array()));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $result = $this->base_model->find_one(9);
        $this->assertNull($result);
    }

    public function test_find_one_returns_correct_value()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->will($this->returnValue(array(100, 200)));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $result = $this->base_model->find_one(9);
        $this->assertEquals(100, $result);
    }

    public function test_find_one_returns_correct_value_if_returns_more_then_one_result()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->will($this->returnValue(array(100, 200)));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $result = $this->base_model->find_one(9, array(), null);
        $this->assertEquals(array(100, 200), $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_delete_throws_invalid_argument_exception()
    {
        $this->base_model->delete('string');
    }

    public function test_delete_calls_db_insert_with_correct_params()
    {
        $this->mock->expects($this->any())
            ->method('delete')
            ->with(
                $this->equalTo('table'),
                $this->equalTo(array('id' => 9))
            );
        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->delete(array('id' => 9));
    }

    public function test_delete_calls_delete_with_correct_parameters_when_integer_is_passed()
    {
        $this->mock->expects($this->any())
            ->method('delete')
            ->with(
                $this->equalTo('table'),
                $this->equalTo(array('id' => 9))
            );

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->delete(9);
    }

    public function test_delete_calls_delete_with_correct_parameters_when_array_is_passed()
    {
        $this->mock->expects($this->any())
            ->method('delete')
            ->with(
                $this->equalTo('table'),
                $this->equalTo(array())
            );

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->delete(array());
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_one_as_array_returns_correct_value()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('find_one')
            ->will($this->returnValue(array(100, 200)));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $result = $this->base_model->find_one_as_array(12);
        $this->assertEquals(100, $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_find_all_throws_invalid_argument_exception()
    {
        $this->mock->expects($this->any())
        ->method('find_all')
        ->with(
            $this->equalTo(array())
        );

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_all(9);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_all_returns_correct_value()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('find_one')
            ->will($this->returnValue(array(100, 200)));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $result = $this->base_model->find_all(array());
        $this->assertEquals(100, $result);
    }

    public function test_find_all_calls_result_with_type_equals_object()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->with($this->equalTo('object'));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_all(array('id' => 4));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_find_all_as_array_throws_invalid_argument_exception()
    {
        $this->mock->expects($this->any())
            ->method('find_all_as_array')
            ->with(
                $this->equalTo(array())
            );

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_all_as_array(9);
    }

    public function test_find_all_as_array_calls_result_with_type_equals_object()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->with($this->equalTo('array'));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->_table = 'table';
        $this->base_model->db = $this->mock;
        $this->base_model->find_all_as_array(array('id' => 4));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_find_all_on_table_throws_invalid_argument_exception()
    {
        $this->mock->expects($this->any())
            ->method('find_all_on_table')
            ->with(
                $this->equalTo(array())
            );

        $this->base_model->db = $this->mock;
        $this->base_model->find_all_on_table(9, 'table');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function test_find_all_on_table_null_table_parameter()
    {
        $this->base_model->find_all_on_table(array(), null, array());
    }

    public function test_find_all_on_table_calls_result_with_type_equals_object()
    {
        $query_mock = $this->getMockBuilder('Fake_CI_DB_result')
            ->disableOriginalConstructor()
            ->getMock();

        $query_mock->expects($this->any())
            ->method('result')
            ->with($this->equalTo('object'));

        $this->mock->expects($this->any())
            ->method('get_where')
            ->will($this->returnValue($query_mock));

        $this->base_model->db = $this->mock;
        $this->base_model->find_all_on_table(array('id' => 4), 'table');
    }

}

class Fake_CI_DB_result
{
    function result() {}
    function result_array() {}
    function find_one(){}
}