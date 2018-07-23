<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		  $client     = new GuzzleHttp\Client();
	
	}
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
