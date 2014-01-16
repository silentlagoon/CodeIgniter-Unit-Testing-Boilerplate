<?php
require_once 'base_model.php';

class Test extends Base_model
{
    protected $table;

    public function Test()
    {
        parent::__construct();
        $this->_table = 'test';
    }

    public function getSample($number)
    {
        return $this->find_one($number);
    }
}