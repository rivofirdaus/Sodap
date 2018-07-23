<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	public function __construct()
	{
		parent::__construct();



	}
	public function index()
	{
		$json_string = 'http://192.168.10.5:8080/mpgrm/22384ee59631a5a61ce3386af63c094b/2018';
		$jsondata = file_get_contents($json_string);
		$obj = json_decode($jsondata, true);
		foreach ($obj['DATA'] as $row) {
			$data[] = array(
	                  "idprgrm"  =>  $row['IDPRGRM'],
	                  "nmprgrm"    =>  $row['NMPRGRM']
	          );
		}

	// 	echo "<table border=1>";
	// foreach ($obj['DATA'] as $row) {
	// 	# code...
	// 	echo "<tr>";
	// 	echo "<td>";
	// 	echo $row['IDPRGRM'];
	// 	echo "</td>";
	// 	echo "<td>";
	// 	echo $row['NMPRGRM'];
	// 	echo "</td>";
	// 	echo "</tr>";
	// }
	// echo "</table>";

	}
}
