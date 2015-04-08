<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Main_Model extends CI_Model
  {
    private $mytable;

    public function __construct()
    {
      parent::__construct();

      $this->mytable = "customers";
    }


    public function getDepartamentos()
    {
      $result = FALSE;
      $this->db->select('id, name');
      $result = $this->db->get('departamentos');

      if ($result->num_rows() > 0) {
        return $result->result();
      }

      return $result;
    }

    public function getCredits(){
      $result = false;
      $this->db->select('id,name');
      $result = $this->db->get('creditos');

      if ($result->num_rows() > 0) {
        return $result->result();
      }
      return $result;
    }

    public function checkCustomer($field,$dni)
    {
      $result = $this->db->get_where($this->mytable , array( $field => $dni));

      if($result->num_rows() > 0){
        return $result->row();
      }
      return false;
    }

    public function insertaCustomer($data)
    {
      if (sizeof($data) > 0) {
          $this->db->insert($this->mytable, $data);
          return $this->db->insert_id();
      }
      return FALSE;
    }

    public function getAll($city = 0, $limit = 0, $offset = 0)
    {
      $result = array();
      $res = FALSE;

      if ((int)$city > 0)
      {
        $this->db->select('id, name, address, phone');
        $this->db->where('id_ciudad', $city);
        $result = $this->db->get('agencias', $limit, $offset);

        if ($result->num_rows() > 0)
        {
          $res['data'] = $result->result();

          $this->db->select('id');

          $this->db->where('id_ciudad', $city);

          $result = $this->db->get('agencias');
          $res['num_rows'] = $result->num_rows();
        }
      }

      return $res;
    }

    public function get($table = '', $select = '', $where = array())
    {
      $table = (empty($table)) ? $this->mytable : $table;

      if (!empty($select))
      {
        $this->db->select($select);
      }

      if (count($where))
      {
        $this->db->where($where);
      }

      $result = $this->db->get($table);

      if ($result->num_rows() > 0)
      {
        return $result->result();
      }

      return FALSE;
    }

    public function getRow($table = '', $select = '', $where = array())
    {
      $table = (empty($table)) ? $this->mytable : $table;

      if (!empty($select))
      {
        $this->db->select($select);
      }

      if (count($where))
      {
        $this->db->where($where);
      }

      $result = $this->db->get($table);

      if ($result->num_rows() > 0)
      {
        return $result->row();
      }

      return FALSE;
    }
  }

