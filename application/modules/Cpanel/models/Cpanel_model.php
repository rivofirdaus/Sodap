<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cpanel_model extends CI_Model
{
  function __construct()
  {
      parent::__construct();
  }
  function addprgrm(){
    $json_string = 'http://192.168.10.5:8080/mpgrm/22384ee59631a5a61ce3386af63c094b/2018';
		$jsondata = file_get_contents($json_string);
		$obj = json_decode($jsondata, true);
		foreach ($obj['DATA'] as $row) {
			$data[] = array(
	                  "idprgrm"  =>  $row['IDPRGRM'],
	                  "nmprgrm"    =>  $row['NMPRGRM']
	          );
		}
    $this->db->trans_start();
    $this->db->insert_batch('mpgrm', $data);
    $this->db->trans_complete();
    return ($this->db->affected_rows()!=1)?false:true;
  }
}
