<?php

	include 'connectdb.php';

	$data = json_decode(file_get_contents("php://input"));

	$search = $data->searchText;

	// Fetch 5 records
	$sel = mysqli_query($conn,"select * from tm_category where CATEGORY_NAME like '%".$search."%' limit 5");
	$data = array();

	while ($row = mysqli_fetch_array($sel)) {
		$data[] = array("name"=>$row['CATEGORY_NAME']);
	}

	echo json_encode($data);
?>