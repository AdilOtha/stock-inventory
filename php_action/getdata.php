<?php
require 'db_connect.php';
if(!empty($_POST['pid'])){
    $id=$_POST['pid'];
    //echo $id;
    //get user data from the database
    $insert = "SELECT * FROM electronics WHERE pid = '$id' ";
    $inresult=$connect->query($insert);
    if($inresult->num_rows>0){
        //echo "success";
        $userData = $inresult->fetch_assoc();
        $data=$userData;
    }
    else{
        //echo "failed";
    }
    echo json_encode($data);
}
?>