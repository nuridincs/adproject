<?php
    include_once "connectdb.php";

    //print_r($_POST);
    
    //print_r($cat);
    /*echo "Data category = ".count($cat);
    die();*/

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $getMaxId = "SELECT MAX(PRO_ID) AS ID FROM tx_project";
    $resMaxId = $conn->query($getMaxId);
    $data = $resMaxId->fetch_row();
    $ID = $data[0];

    if ($ID == NULL) {
        $IDDATA = 1;
    } else {
        $IDDATA = $ID+1;
    }

    $sqlProject = "INSERT INTO tx_project VALUES ('".$IDDATA."','".$_POST['user_id']."','".$_POST['title']."','t_pc','".$_POST['description']."','".$_POST['location']."','".$_POST['regional']."','".$_POST['country']."','".$_POST['budget']."','".date('Y-m-d H:i:s')."','PS03','".$_POST['startdate']."','".$_POST['enddate']."','".$_POST['windate']."')";

    if ($conn->query($sqlProject) === TRUE) {
        echo json_encode(array('result' => 'Insert Porject Berhasil')); // new file uploaded

        $cat = explode(',',$_POST['CodeCategory']);
        $rowsCat = count($cat);
        for ($i=0; $i < $rowsCat; $i++) { 
            $SQLInCode = "INSERT INTO tx_project_category VALUES('".$IDDATA."', $cat[$i])";
            if ($conn->query($SQLInCode) === TRUE) {
                echo json_encode(array('result' => 'Insert tc Porject Berhasil')); // new file uploaded
            } else {
                echo "Error: " . $SQLInCode . "<br>" . $conn->error;
            }
        }
        
    } else {
        echo "Error: " . $sqlProject . "<br>" . $conn->error;
    }

    $getMaxIdFile = "SELECT MAX(PRO_DOC_ID) AS ID FROM tx_project_doc";
    $resMaxIdFile = $conn->query($getMaxIdFile);
    $dataFile = $resMaxIdFile->fetch_row();
    $IDDocFile = $dataFile[0];

    if ($IDDocFile == NULL) {
        $IDFile = 1;
    } else {
        $IDFile = $IDDocFile+1;
    }

     $target_dir = "../../assets/images/uploads/project/";
     $target_file = $target_dir . basename($_FILES["file"]["name"]);

     if (basename($_FILES["file"]["name"]) == "") {
        $file = 'no_images.png';
    }else{
        $file = basename($_FILES["file"]["name"]);
    }

     move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

     $sql = "INSERT INTO tx_project_doc VALUES ('".$IDFile."','".$IDDATA."','".$file."','".date('Y-m-d H:i:s')."')";
     //basename($_FILES["file"]["name"])

     if ($conn->query($sql) === TRUE) {
         echo json_encode($_FILES["file"]); // new file uploaded
     } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
     }

     $conn->close();

?>