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
  }

