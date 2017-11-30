<?php
header("Access-Control-Allow-Origin: *");

include("koneksi.php");

if($_GET['do'] == "login"){
    $sql = mysqli_query($con, "SELECT * FROM user WHERE nis='".$_POST['nis']."' AND password='".$_POST['pass']."'");
    $data = array();
    if(mysqli_num_rows($sql) != 0){
        $r = mysqli_fetch_assoc($sql);
        $data[0] = "sukses";
        $data[1] = $r;
        echo json_encode($data);
    }
    else {
        $data[0] = "gagal";
        echo json_encode($data);
    }
}

else if($_GET['do'] == "register"){
    $sql = mysqli_query($con, "INSERT INTO user VALUES('$_POST[nis]', '$_POST[pass]', '$_POST[nama]', '$_POST[role]')");
    echo mysqli_insert_id($con);
}

?>