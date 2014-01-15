<?php

class Base_model extends CI_Model
{
    function Base_model()
    {
        parent::__construct();
    }

    function find_one($params, $options = array())
    {
        $options = array_merge(
            array(
                'return_type' => 'object',
                'table' => $this->_table
            ),$options);

        $query = NULL;
        if (is_int($params)) {
            $query = $this->db->get_where($options['table'], array('id' => $params), 1);
        }
        elseif (is_array($params)) {
            $query = $this->db->get_where($options['table'], $params, 1);
        } else {
            throw new InvalidArgumentException('params can be integer or array only. Input was: '.$params);
        }

        if (is_null($query)) {
            throw new UnexpectedValueException('query parameter cannot be NULL');
        }

        $result = $query->result($options['return_type']);
        return count($result) > 0 ? $result[0] : NULL;
    }

    function find_one_as_array($params, $options = array())
    {
        $options['return_type'] = 'array';
        return $this->find_one($params, $options);
    }

    function find_all($params, $options = array())
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('params can be array only. Input was: '.$params);
        }

        $options = array_merge(
            array(
                'result_as_array' => FALSE,
                'table' => $this->_table
            ),$options);

        $query = $this->db->get_where($options['table'], $params);
        if ($options['result_as_array'] === TRUE) {
            $result = $query->result_array();
        } else {
            $result = $query->result();
        }

        return $result;
    }

    function find_all_as_array($params, $options = array())
    {
        $options['result_as_array'] = TRUE;
        return $this->find_all($params, $options);
    }

    function create($params)
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('options can be array only. Input was: '.$params);
        }
        $this->db->insert($this->_table, $params);
    }

    function delete($options)
    {
        if (is_int($options)) {
            $this->db->where('id', $options)->delete($this->_table);
        }
        else if (is_array($options)) {
            $this->db->where($options)->delete($this->_table);
        } else {
            throw new InvalidArgumentException('options can be integer or array only. Input was: '.$options);
        }
    }

    function find_all_on_table($params, $table, $options = array())
    {
        $options['table'] = $table;
        return $this->find_all($params, $options);
    }
}