<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpanel extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		 $this->load->model(array('Cpanel_model'));

	}
	public function index()
	{

	}

	function apiprgrm(){
		$result=$this->Cpanel_model->addprgrm();
	  echo $result;

	}
}
