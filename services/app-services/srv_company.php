<?php
    include_once "connectdb.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $getMaxId = "SELECT MAX(COMP_ID) AS ID FROM tm_company";
    $resMaxId = $conn->query($getMaxId);
    $data = $resMaxId->fetch_row();
    $ID = $data[0];

    if ($ID == NULL) {
        $IDDATA = 1;
    } else {
        $IDDATA = $ID+1;
    }

    $target_dir = "../../assets/images/uploads/company/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (basename($_FILES["file"]["name"]) == "") {
        $file = 'no_images.png';
    }else{
        $file = basename($_FILES["file"]["name"]);
    }

    //print_r($file);die();

    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $sql = "INSERT INTO tm_company VALUES ('".$IDDATA."','".$_POST['user_id']."','".$_POST['npwp']."','".$_POST['company']."','".$_POST['address']."','".$_POST['regional']."','".$_POST['country']."','".$_POST['telephone']."','".$_POST['fax']."','".$file."','0','PSO1')";

    if ($conn->query($sql) === TRUE) {
         echo json_encode($_FILES["file"]); // new file uploaded
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

?>