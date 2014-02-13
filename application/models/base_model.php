<?php

class Base_model extends CI_Model
{
    function Base_model()
    {
        parent::__construct();
    }

    /**
     * @param $params
     * @param array $options
     * @param int $limit
     * @return null
     * @throws UnexpectedValueException
     * @throws InvalidArgumentException
     */
    function find_one($params, $options = array(), $limit=1)
    {
        $options = array_merge(
            array(
                'return_type' => 'object',
                'table' => $this->_table,
                'limit' => $limit
            ),$options);

        $query = NULL;
        if (is_int($params)) {
            $query = $this->db->get_where($options['table'], array('id' => $params), $options['limit']);
        }
        elseif (is_array($params)) {
            $query = $this->db->get_where($options['table'], $params, $options['limit']);
        } else {
            throw new InvalidArgumentException('params can be integer or array only. Input was: '.$params);
        }

        if (is_null($query)) {
            throw new UnexpectedValueException('query parameter cannot be NULL');
        }

        $result = $query->result($options['return_type']);
        if($options['limit'])
        {
            return count($result) > 0 ? $result[0] : NULL;
        }
        return count($result) > 0 ? $result : NULL;
    }

    /**
     * @param $params
     * @param array $options
     * @return null
     */
    function find_one_as_array($params, $options = array())
    {
        $options['return_type'] = 'array';
        return $this->find_one($params, $options);
    }

    /**
     * @param $params
     * @param array $options
     * @return null
     * @throws InvalidArgumentException
     */
    function find_all($params, $options = array())
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('params can be array only. Input was: '.$params);
        }
        $options['limit'] = null;
        return $this->find_one($params, $options);
    }

    /**
     * @param $params
     * @param array $options
     * @return null
     * @throws InvalidArgumentException
     */
    function find_all_as_array($params, $options = array())
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('params can be array only. Input was: '.$params);
        }
        $options['return_type'] = 'array';
        $options['limit'] = null;
        return $this->find_one($params, $options);
    }

    /**
     * @param $params
     * @throws InvalidArgumentException
     */
    function create($params)
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('options can be array only. Input was: '.$params);
        }
        $this->db->insert($this->_table, $params);
    }

    /**
     * @param $options
     * @throws InvalidArgumentException
     */
    function delete($options)
    {
        if (is_int($options)) {
            $this->db->delete($this->_table, array('id' => $options));
        }
        else if (is_array($options)) {
            $this->db->delete($this->_table, $options);
        } else {
            throw new InvalidArgumentException('options can be integer or array only. Input was: '.$options);
        }
    }

    /**
     * @param $params
     * @throws InvalidArgumentException
     */
    public function update($params)
    {
        if (is_int($params)) {
            $this->db->update($this->table, array('id' => $params));
        }
        else if (is_array($params)) {
            $this->db->update($this->table, $params);
        } else {
            throw new InvalidArgumentException('Options can be integer or array only. Input was: '.$params);
        }
    }

    /**
     * @param $params
     * @param $table
     * @param array $options
     * @return null
     * @throws UnexpectedValueException
     * @throws InvalidArgumentException
     */
    function find_all_on_table($params, $table, $options = array())
    {
        if (!is_array($params)) {
            throw new InvalidArgumentException('params can be array only. Input was: '.$params);
        }
        if($table)
        {
            $options['table'] = $table;
            $options['limit'] = null;
            return $this->find_one($params, $options);
        }
        throw new UnexpectedValueException('table parameter cannot be NULL');
    }
}