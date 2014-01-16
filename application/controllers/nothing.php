<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nothing extends CI_Controller
{

    public function Nothing()
    {
        parent::__construct();
        $this->load->model('test');
    }

    public function index()
    {
        $number = $this->test->getSample(1);
        if(is_object($number)) {
            $this->load->view('number', array('number' => $number->id));
        }
       else {
           throw new InvalidArgumentException ('Database argument problem');
       }
    }
}