<?php 
	define("HOSTNAME","localhost");
	define("USERNAME","root");
	define("PASSWORD","");
	define("DATABASE","adaproyek");

	$conn = new mysqli(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die ("unable to Connect DB");
 ?>